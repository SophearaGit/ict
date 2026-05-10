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

        // update field
        $report->{$request->field} = $request->value;

        /*
        |--------------------------------------------------------------------------
        | Total Score
        |--------------------------------------------------------------------------
        |
        | attendance = 10
        | assignment = 30
        | mini = 20
        | final = 40
        |
        | TOTAL = 100
        |
        */

        $total =
            (float) $report->attendance_score +
            (float) $report->assignment_score +
            (float) $report->mini_project_score +
            (float) $report->final_project_score;

        $report->total_score = round($total, 2);

        $report->result = $total >= 50
            ? 'pass'
            : 'fail';

        $report->save();

        return response()->json([
            'total_score' => $report->total_score,
            'result' => $report->result
        ]);
    }
}
