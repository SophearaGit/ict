<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternReport;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class InternReportController extends Controller
{
    public function index(Request $request): View
    {
        $years = InternReport::selectRaw('YEAR(created_at) as y')
            ->distinct()
            ->orderByDesc('y')
            ->pluck('y');

        $reports = InternReport::with(['reporter', 'reviewedByAdmin', 'reviewedByStaff'])
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
            ->when($request->sort == 'intern_asc', function ($query): void {
                $query->whereHas('reporter')
                    ->orderBy(
                        User::select('name')
                            ->whereColumn('users.id', 'intern_reports.reported_by')
                    );
            })
            ->when($request->sort == 'intern_desc', function ($query): void {
                $query->whereHas('reporter')
                    ->orderByDesc(
                        User::select('name')
                            ->whereColumn('users.id', 'intern_reports.reported_by')
                    );
            })
            ->when(!$request->filled('sort'), function ($query): void {
                $query->latest();
            })
            ->paginate(10)
            ->appends($request->query());

        $counts = [
            'all' => InternReport::count(),
            'pending' => InternReport::where('status', 'pending')->count(),
            'reviewed' => InternReport::where('status', 'reviewed')->count(),
        ];

        $data = [
            'page_title' => 'Intern Reports',
            'years' => $years,
            'reports' => $reports,
            'counts' => $counts,
        ];

        return view('admin.pages.Report.Intern.index', $data);
    }

    public function review(string $id): Response
    {
        $report = InternReport::findOrFail($id);

        $data = [
            'status' => 'reviewed',
            'reviewed_at' => now(),
        ];

        if ($adminId = Auth::guard('admin')->id()) {
            $data['reviewed_by_admin_id'] = $adminId;
            $data['reviewed_by_staff_id'] = null;
        } elseif ($staffId = Auth::guard('web')->id()) { // adjust guard name if staff use a different one
            $data['reviewed_by_staff_id'] = $staffId;
            $data['reviewed_by_admin_id'] = null;
        } else {
            return response(['message' => 'Unauthorized reviewer.'], 403);
        }

        $report->update($data);

        return response(['message' => 'Report marked as reviewed.'], 200);
    }
}
