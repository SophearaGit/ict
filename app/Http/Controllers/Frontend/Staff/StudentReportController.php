<?php

namespace App\Http\Controllers\Frontend\Staff;

use App\Http\Controllers\Controller;
use App\Models\StudentReports;
use Illuminate\Http\Request;

class StudentReportController extends Controller
{
    public function update(Request $request, $id)
    {
        $report = StudentReports::findOrFail($id);

        $report->{$request->field} = $request->value;

        // Recalculate
        $total =
            ($report->attendance_score * 0.10) +
            ($report->assignment_score * 0.20) +
            ($report->mini_project_score * 0.20) +
            ($report->final_project_score * 0.50);

        $report->total_score = round($total, 2);
        $report->result = $total >= 50 ? 'pass' : 'fail';

        $report->save();

        return response()->json([
            'total_score' => $report->total_score,
            'result' => $report->result
        ]);
    }
}
