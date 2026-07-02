<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ICTStaffReport;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class StaffReportController extends Controller
{
    public function index(Request $request): View
    {
        $years = ICTStaffReport::selectRaw('YEAR(created_at) as y')
            ->distinct()
            ->orderByDesc('y')
            ->pluck('y');

        $reports = ICTStaffReport::with(['staff', 'reviewer'])
            ->when($request->filled('status'), function ($query) use ($request): void {
                $query->where('status', $request->status);
            })
            ->when($request->filled('search'), function ($query) use ($request): void {
                $query->where('report_content', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('year'), function ($query) use ($request): void {
                $query->whereYear('created_at', $request->year);
            })
            ->when($request->filled('month'), function ($query) use ($request): void {
                $query->whereMonth('created_at', $request->month);
            })
            ->when($request->filled('week'), function ($query) use ($request): void {
                $query->whereRaw('CEIL(DAY(created_at)/7) = ?', [$request->week]);
            })
            ->when($request->sort == 'staff_asc', function ($query): void {
                $query->whereHas('staff')
                    ->orderBy(
                        User::select('name')
                            ->whereColumn('users.id', 'i_c_t_staff_reports.reported_by')
                    );
            })
            ->when($request->sort == 'staff_desc', function ($query): void {
                $query->whereHas('staff')
                    ->orderByDesc(
                        User::select('name')
                            ->whereColumn('users.id', 'i_c_t_staff_reports.reported_by')
                    );
            })
            ->when(!$request->filled('sort'), function ($query): void {
                $query->latest();
            })
            ->paginate(10)
            ->appends($request->query());

        $data = [
            'page_title' => 'Staff Reports',
            'years' => $years,
            'reports' => $reports,
        ];

        return view('admin.pages.Report.Staff.index', $data);
    }

    public function review(string $id): Response
    {
        $report = ICTStaffReport::findOrFail($id);

        $report->update([
            'status' => 'reviewed',
            'reviewed_by' => Auth::guard('admin')->id(),
            'reviewed_at' => now(),
        ]);

        return response(['message' => 'Report marked as reviewed.'], 200);
    }
}
