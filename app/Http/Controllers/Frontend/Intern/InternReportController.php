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
        $reports->whereBetween('created_at', [$start, $end]);
        $reports = $reports
            ->orderBy($column, $direction)
            ->paginate(10)
            ->appends($request->query());
        return view('frontend.Intern.pages.report', [
            'page_title' => 'Reports - ICT',
            'reports' => $reports,
        ]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_content' => 'required|string',
        ]);
        $validated['reported_by'] = Auth::id();
        InternReport::create($validated);
        return back()->with('success', 'Report submitted successfully.');
    }
    public function update(Request $request, InternReport $report)
    {
        abort_if($report->reported_by !== Auth::id(), 403);
        $validated = $request->validate([
            'report_content' => 'required|string',
        ]);
        $report->update($validated);
        return back()->with('success', 'Report updated successfully.');
    }
    public function destroy(InternReport $report)
    {
        abort_if($report->reported_by !== Auth::id(), 403);
        $report->delete();
        return back()->with('success', 'Report deleted successfully.');
    }
}
