<?php
namespace App\Http\Controllers\Frontend\Intern;
use App\Http\Controllers\Controller;
use App\Models\InternReport;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
class InternReportController extends Controller
{
    public function index(Request $request)
    {
        $sortOptions = [
            'newest' => ['created_at', 'desc'],
            'oldest' => ['created_at', 'asc'],
        ];
        $sort = $request->get('sort', 'newest');
        [$column, $direction] = $sortOptions[$sort] ?? $sortOptions['newest'];
        $reports = InternReport::query()
            ->where('reported_by', Auth::id());
        // Default to current month
        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) === 2) {
                $start = Carbon::createFromFormat('d M Y', trim($dates[0]))->startOfDay();
                $end = Carbon::createFromFormat('d M Y', trim($dates[1]))->endOfDay();
            } else {
                $start = Carbon::now()->startOfMonth();
                $end = Carbon::now()->endOfDay();
            }
        } else {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfDay();
        }
        // Filters against the period a report covers, not created_at — a
        // backdated report's submission timestamp can fall outside the picked
        // range even when the work it describes falls inside it.
        $reports->where(function ($q) use ($start, $end) {
            $q->whereBetween('period_start', [$start, $end])
                ->orWhereBetween('period_end', [$start, $end])
                ->orWhere(function ($q2) use ($start, $end) {
                    $q2->where('period_start', '<=', $start)->where('period_end', '>=', $end);
                });
        });
        $reports = $reports
            ->orderBy($column, $direction)
            ->paginate(10)
            ->appends($request->query());
        return view('frontend.Intern.pages.report', [
            'page_title' => 'Reports - ICT',
            'reports' => $reports,
        ]);
    }
    /**
     * True if the current user already has another report whose period
     * overlaps [start, end] — covers exact duplicates too, since an identical
     * range is just the tightest possible overlap. $excludeId lets update()
     * and updatePeriod() ignore the report being edited against itself.
     */
    private function hasOverlappingPeriod(string $start, string $end, ?int $excludeId = null): bool
    {
        return InternReport::where('reported_by', Auth::id())
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->where('period_start', '<=', $end)
            ->where('period_end', '>=', $start)
            ->exists();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_content' => 'required|string',
            'period_start' => 'nullable|date|before_or_equal:today',
            'period_end' => 'nullable|date|after_or_equal:period_start|before_or_equal:today',
        ]);
        $periodStart = $validated['period_start'] ?? now()->format('Y-m-d');
        $periodEnd = $validated['period_end'] ?? $periodStart;

        if ($this->hasOverlappingPeriod($periodStart, $periodEnd)) {
            return back()
                ->withErrors(['period_start' => 'You already have a report covering part of this period.'])
                ->withInput();
        }

        $validated['reported_by'] = Auth::id();
        // Default to today for both when no period is picked, so a report is
        // never saved with a blank period.
        $validated['period_start'] = $periodStart;
        $validated['period_end'] = $periodEnd;
        InternReport::create($validated);
        return back()->with('success', 'Report submitted successfully.');
    }
    public function update(Request $request, InternReport $report)
    {
        abort_if($report->reported_by !== Auth::id(), 403);
        $validated = $request->validate([
            'report_content' => 'required|string',
            'period_start' => 'nullable|date|before_or_equal:today',
            'period_end' => 'nullable|date|after_or_equal:period_start|before_or_equal:today',
        ]);
        if (!empty($validated['period_start'])) {
            $validated['period_end'] = $validated['period_end'] ?? $validated['period_start'];

            if ($this->hasOverlappingPeriod($validated['period_start'], $validated['period_end'], $report->id)) {
                return back()
                    ->withErrors(['period_start' => 'You already have a report covering part of this period.'])
                    ->withInput();
            }
        } else {
            unset($validated['period_start'], $validated['period_end']);
        }
        $report->update($validated);
        return back()->with('success', 'Report updated successfully.');
    }

    /**
     * Update only the report's covered period, used by the "Change Period"
     * flatpickr range picker in the list/grid views. Kept separate from
     * update() since that also re-validates/re-submits report_content, and
     * stays JSON/AJAX rather than back()->with() so it doesn't need a full
     * page reload just to change two dates.
     */
    public function updatePeriod(Request $request, InternReport $report)
    {
        abort_if($report->reported_by !== Auth::id(), 403);

        $validated = $request->validate([
            'period_start' => 'required|date|before_or_equal:today',
            'period_end' => 'required|date|after_or_equal:period_start|before_or_equal:today',
        ]);

        if ($this->hasOverlappingPeriod($validated['period_start'], $validated['period_end'], $report->id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'This period overlaps with another report you already submitted.',
            ], 422);
        }

        $report->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Report period updated successfully.',
        ]);
    }

    public function destroy(InternReport $report)
    {
        abort_if($report->reported_by !== Auth::id(), 403);
        $report->delete();
        return back()->with('success', 'Report deleted successfully.');
    }
}
