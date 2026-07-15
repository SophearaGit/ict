<?php
namespace App\Http\Controllers\Frontend\Staff;
use App\Http\Controllers\Controller;
use App\Models\ICTStaffReport;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
class IctStaffReportController extends Controller
{
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
        return view('frontend.staff.pages.report.report', [
            'page_title' => 'ICT | Staff | Reports',
            'reports' => $reports,
        ]);
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request): Response
    {
        $validated = $request->validate([
            'report_content' => ['required', 'string'],
        ]);
        ICTStaffReport::create([
            'reported_by' => Auth::id(),
            'report_content' => $validated['report_content'],
            'status' => 'pending',
        ]);
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
        ]);
        $report->update([
            'report_content' => $validated['report_content'],
            'status' => 'pending', // Re-submit for approval
        ]);
        return response([
            'status' => 'success',
            'message' => 'Report updated successfully.',
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
}
