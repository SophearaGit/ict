<?php
namespace App\Http\Controllers\Frontend\Intern;
use App\Http\Controllers\Controller;
use App\Models\InternReport;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
class InternDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $query = InternReport::where('reported_by', Auth::id());
        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) === 2) {
                $query->whereBetween('created_at', [
                    Carbon::parse(trim($dates[0]))->startOfDay(),
                    Carbon::parse(trim($dates[1]))->endOfDay(),
                ]);
            }
        }
        $data = [
            'page_title' => 'Intern Dashboard - ICT',
            'reportCount' => $query->count(),
        ];
        return view('frontend.Intern.pages.index', $data);
    }
}
