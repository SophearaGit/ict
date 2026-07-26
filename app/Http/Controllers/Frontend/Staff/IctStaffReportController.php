<?php
namespace App\Http\Controllers\Frontend\Staff;
use App\Http\Controllers\Controller;
use App\Models\ICTStaffReport;
use App\Models\InternReport;
use App\Models\User;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class IctStaffReportController extends Controller
{
    /**
     * Statuses a report can be filtered/transitioned to. Kept in one place so the
     * legend, counts, and validation below all agree with each other.
     */
    private const STATUSES = ['pending', 'reviewed'];

    /**
     * Maps a report status to the calendar bar color + Bootstrap-style class
     * suffix used across badges/legend chips, so both stay in sync.
     */
    private const STATUS_STYLE = [
        'pending' => ['hex' => '#FFAE1F', 'class' => 'warning'],
        'reviewed' => ['hex' => '#13DEB9', 'class' => 'success'],
    ];

    /**
     * Which Eloquent model backs each role's reports. Interns get their own
     * table now (InternReport), so roleReportView() can't hardcode
     * ICTStaffReport for every role — it has to look this up per role.
     *
     * NOTE: 'student' is left pointed at ICTStaffReport as a guess, matching
     * what it was before. If students also have their own dedicated model
     * (the way interns now do), this needs the same fix as 'intern' did.
     */
    private const ROLE_MODEL = [
        'student' => ICTStaffReport::class,
        'staff' => ICTStaffReport::class,
        'intern' => InternReport::class,
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $perPage = $request->integer('per_page', 10);
        $reports = ICTStaffReport::where('reported_by', Auth::id())
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('report_content', 'like', '%' . $request->search . '%');
            })
            ->latest()
            ->paginate($perPage)
            ->appends($request->query());

        $reports->getCollection()->transform(function ($report) {
            $report->period_label = $this->formatPeriodLabel($report);
            return $report;
        });

        return view('frontend.staff.pages.report.report', [
            'page_title' => 'ICT | Staff | Reports',
            'reports' => $reports,
        ]);
    }

    /**
     * "Jul 21, 2026" for a single-day period, or "Jul 21 – Jul 25, 2026" for a
     * range. Falls back to created_at for legacy rows with no period set.
     * Accepts either report model, since this is shared by the review screens
     * across roles now.
     */
    private function formatPeriodLabel(ICTStaffReport|InternReport $report): string
    {
        $start = \Carbon\Carbon::parse($report->period_start ?? $report->created_at);
        $end = \Carbon\Carbon::parse($report->period_end ?? $report->period_start ?? $report->created_at);

        return $start->isSameDay($end)
            ? $start->format('M d, Y')
            : $start->format('M d') . ' – ' . $end->format('M d, Y');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $data = [
            'page_title' => 'ICT | Staff | Create Report',
        ];
        return view('frontend.staff.pages.report.add', $data);
    }
    /**
     * True if this staff member already has another report whose period
     * overlaps [start, end] — covers exact duplicates too, since an identical
     * range is just the tightest possible overlap. $excludeId lets update()
     * and updatePeriod() ignore the report being edited against itself.
     */
    private function hasOverlappingPeriod(string $start, string $end, ?int $excludeId = null): bool
    {
        return ICTStaffReport::where('reported_by', Auth::id())
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->where('period_start', '<=', $end)
            ->where('period_end', '>=', $start)
            ->exists();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): Response
    {
        $validated = $request->validate([
            'report_content' => ['required', 'string'],
            'period_start' => ['nullable', 'date', 'before_or_equal:today'],
            'period_end' => ['nullable', 'date', 'after_or_equal:period_start', 'before_or_equal:today'],
        ]);

        $periodStart = $validated['period_start'] ?? now()->format('Y-m-d');
        $periodEnd = $validated['period_end'] ?? $periodStart;

        if ($this->hasOverlappingPeriod($periodStart, $periodEnd)) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'period_start' => ['You already have a report covering part of this period.'],
                ],
            ], 422);
        }

        $report = new ICTStaffReport();
        $report->reported_by = Auth::id();
        $report->report_content = $validated['report_content'];
        $report->status = 'pending';

        // Default to a single-day period (today) when the staff member doesn't
        // pick a range — created_at is left alone either way, since it's pure
        // audit metadata now, not the thing that defines the covered period.
        $report->period_start = $periodStart;
        $report->period_end = $periodEnd;

        $report->save();

        return response([
            'status' => 'success',
            'message' => 'Report submitted successfully.',
        ], 201);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $report = ICTStaffReport::where('reported_by', Auth::id())->findOrFail($id);
        $data = [
            'page_title' => 'ICT | Staff | Edit Report',
            'report' => $report,
        ];
        return view('frontend.staff.pages.report.edit', $data);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): Response
    {
        $report = ICTStaffReport::where('reported_by', Auth::id())
            ->findOrFail($id);
        $validated = $request->validate([
            'report_content' => ['required', 'string'],
            'period_start' => ['nullable', 'date', 'before_or_equal:today'],
            'period_end' => ['nullable', 'date', 'after_or_equal:period_start', 'before_or_equal:today'],
        ]);
        $report->report_content = $validated['report_content'];
        $report->status = 'pending'; // Re-submit for approval

        if (!empty($validated['period_start'])) {
            $periodEnd = $validated['period_end'] ?? $validated['period_start'];

            if ($this->hasOverlappingPeriod($validated['period_start'], $periodEnd, $report->id)) {
                return response()->json([
                    'message' => 'The given data was invalid.',
                    'errors' => [
                        'period_start' => ['You already have a report covering part of this period.'],
                    ],
                ], 422);
            }

            $report->period_start = $validated['period_start'];
            $report->period_end = $periodEnd;
        }

        $report->save();

        return response([
            'status' => 'success',
            'message' => 'Report updated successfully.',
        ]);
    }
    /**
     * Update only the report's covered period (period_start/period_end), used
     * by the "Change Period" flatpickr range picker on each report card. Kept
     * separate from update() since that method also re-validates/re-submits
     * report_content.
     */
    public function updatePeriod(Request $request, string $id): Response
    {
        $report = ICTStaffReport::where('reported_by', Auth::id())
            ->findOrFail($id);

        $validated = $request->validate([
            'period_start' => ['required', 'date', 'before_or_equal:today'],
            'period_end' => ['required', 'date', 'after_or_equal:period_start', 'before_or_equal:today'],
        ]);

        if ($this->hasOverlappingPeriod($validated['period_start'], $validated['period_end'], $report->id)) {
            return response([
                'status' => 'error',
                'message' => 'This period overlaps with another report you already submitted.',
            ], 422);
        }

        $report->period_start = $validated['period_start'];
        $report->period_end = $validated['period_end'];
        $report->save();

        return response([
            'status' => 'success',
            'message' => 'Report period updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): Response
    {
        try {
            $report = ICTStaffReport::where('reported_by', Auth::id())
                ->findOrFail($id);
            $report->delete();
            return response([
                'status' => 'success',
                'message' => 'Report deleted successfully.',
                'redirect_url' => route('staff.reports.index'),
            ]);
        } catch (Exception $e) {
            logger()->error($e);
            return response([
                'status' => 'error',
                'message' => 'Something went wrong.',
            ], 500);
        }
    }

    /*******************************************************
     * REVIEW ENDPOINTS
     * Reachable only by staff with the report-review grant
     * (enforced by the `report.grant` route middleware).
     *******************************************************/

    /**
     * Reports submitted by users with role = student.
     */
    public function studentReports(Request $request): View
    {
        return $this->roleReportView($request, 'student', 'Student Report');
    }

    /**
     * Reports submitted by users with role = staff.
     */
    public function staffReports(Request $request): View
    {
        return $this->roleReportView($request, 'staff', 'Staff Report');
    }

    /**
     * Reports submitted by users with role = intern.
     */
    public function internReports(Request $request): View
    {
        return $this->roleReportView($request, 'intern', 'Intern Report');
    }

    /**
     * Shared query + view logic for the three role-based review screens above.
     * Renders as a calendar: reports are handed to the view as FullCalendar-ready
     * events (one bar per report, colored by status) rather than a paginated
     * table, so date navigation replaces the old year/month/week filters.
     */
    private function roleReportView(Request $request, string $role, string $label): View
    {
        $reporterUsers = User::where('role', $role)->orderBy('name')->get(['id', 'name']);
        $reporterIds = $reporterUsers->pluck('id');

        // Interns (and possibly other roles later) live in their own report
        // model/table now — resolve which one to query instead of assuming
        // every role's reports sit in ICTStaffReport.
        $modelClass = self::ROLE_MODEL[$role] ?? ICTStaffReport::class;

        $baseQuery = $modelClass::whereIn('reported_by', $reporterIds)
            ->where('reported_by', '!=', Auth::id());

        // Totals across the whole role, independent of the search/account
        // filters below — these drive the legend chip counts.
        $counts = [
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'reviewed' => (clone $baseQuery)->where('status', 'reviewed')->count(),
        ];

        $reports = (clone $baseQuery)
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('report_content', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('account') && $request->account !== 'all', function ($q) use ($request) {
                $q->where('reported_by', $request->account);
            })
            ->orderByDesc('created_at')
            ->get();

        $reportersById = User::whereIn('id', $reports->pluck('reported_by')->unique())
            ->get()
            ->keyBy('id');

        $events = $reports->map(function ($report) use ($reportersById, $role) {
            $reporter = $reportersById->get($report->reported_by);
            $style = self::STATUS_STYLE[$report->status] ?? ['hex' => '#5A6A85', 'class' => 'secondary'];

            // Explicitly formatted rather than relying on how each model casts
            // these columns — InternReport casts period_start/period_end to
            // Carbon (date:Y-m-d), ICTStaffReport doesn't, so leaving them
            // as-is here would silently send FullCalendar a full ISO
            // timestamp for one role and a plain date for another.
            $periodStart = \Carbon\Carbon::parse($report->period_start ?? $report->created_at)->format('Y-m-d');
            $periodEndRaw = \Carbon\Carbon::parse($report->period_end ?? $report->period_start ?? $report->created_at)->format('Y-m-d');
            // FullCalendar's `end` is exclusive, so a single-day report
            // (period_start == period_end) still needs end = start + 1 day to
            // render at all; multi-day periods just add one day past the
            // actual last day covered.
            $periodEnd = \Carbon\Carbon::parse($periodEndRaw)->addDay()->format('Y-m-d');
            $periodLabel = $this->formatPeriodLabel($report);

            return [
                'id' => $report->id,
                'title' => $reporter->name ?? 'Unknown user',
                'start' => $periodStart,
                'end' => $periodEnd,
                'allDay' => true,
                // No hardcoded background color here — the view applies
                // bg-light-{status}/text-{status} utility classes instead, the
                // same "soft" tint convention already used by the status
                // badges/legend chips elsewhere in this theme.
                'extendedProps' => [
                    'status' => $report->status,
                    'statusClass' => $style['class'],
                    'reporterName' => $reporter->name ?? 'Unknown user',
                    'reporterRole' => $reporter->designation ?? ucfirst($role),
                    'reporterImage' => $reporter && $reporter->image && $reporter->image !== 'no-img.jpg'
                        ? asset($reporter->image)
                        : asset('/admin/assets/dist/images/profile/user-1.jpg'),
                    'content' => $report->report_content,
                    'periodLabel' => $periodLabel,
                    'submittedAt' => $report->created_at->format('M d, Y \a\t h:i A'),
                ],
            ];
        })->values();

        // Human-readable chips for whatever filters are currently active, so the
        // view can render them as removable pills.
        $activeFilters = [];
        if ($request->filled('search')) {
            $activeFilters['search'] = 'Search: "' . $request->search . '"';
        }
        if ($request->filled('account') && $request->account !== 'all') {
            $accountName = optional($reporterUsers->firstWhere('id', (int) $request->account))->name ?? 'Unknown';
            $activeFilters['account'] = 'Account: ' . $accountName;
        }

        return view('frontend.staff.pages.report.review', [
            'page_title' => 'ICT | Staff | ' . $label,
            'heading' => $label,
            'role' => $role,
            'counts' => $counts,
            'reporters' => $reporterUsers,
            'activeFilters' => $activeFilters,
            'events' => $events,
        ]);
    }
}
