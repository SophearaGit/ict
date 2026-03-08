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
        $data = [
            'page_title' => 'ICT | Staff | Reports',
            // 'courses' => Course::with('instructor')
            //     ->when($request->filled('status'), function ($query) use ($request) {
            //         $query->where('is_approved', $request->status);
            //     })
            //     ->when($request->filled('search'), function ($query) use ($request) {
            //         $query->where('title', 'like', '%' . $request->search . '%');
            //     })
            //     ->latest()
            //     ->paginate(10),
            'reports' => ICTStaffReport::where('reported_by', Auth::id())
                ->when($request->filled('status'), function ($query) use ($request): void {
                    $query->where('status', $request->status);
                })
                ->when($request->filled('search'), function ($query) use ($request): void {
                    $query->where('report_content', 'like', '%' . $request->search . '%');
                })
                ->latest()
                ->paginate(10),
        ];
        return view('frontend.staff.pages.report.report', $data);
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
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'report_content' => 'required|string'
        ]);

        ICTStaffReport::create([
            'reported_by' => Auth::id(),
            'report_content' => $request->report_content,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Report submitted successfully.');
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
    public function update(Request $request, string $id)
    {
        $report = ICTStaffReport::where('reported_by', Auth::id())->findOrFail($id);

        $request->validate([
            'report_content' => 'required|string'
        ]);

        $report->update([
            'report_content' => $request->report_content,
            'status' => 'pending' // Reset status to pending on update
        ]);

        return back()->with('success', 'Report updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): Response
    {
        try {
            $report = ICTStaffReport::where('reported_by', Auth::id())->findOrFail($id);
            $report->delete();
            return response([
                'message' => 'Report deleted successfully!',
                'status' => 'success',
                'redirect_url' => route('staff.reports.index'),
            ], 200);
        } catch (Exception $e) {
            logger("Lesson Error >>" . $e);
            return response(['message' => 'Something when wrong!', 500]);
        }
    }
}
