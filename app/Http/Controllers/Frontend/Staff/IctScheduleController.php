<?php

namespace App\Http\Controllers\Frontend\Staff;

use App\Http\Controllers\Controller;
use App\Models\ICTSchedule;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class IctScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $perPage = request('per_page', 10);
        $data = [
            'page_title' => 'ICT Center | Schedules',
            // 'schedules' => ICTSchedule::latest()->paginate(10),
            'schedules' => ICTSchedule::orderBy('study_day')
                ->orderBy('start_time')
                ->paginate($perPage)
                ->withQueryString(),
        ];
        return view('frontend.staff.pages.course-management.schedule-managment.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $data = [
            'page_title' => 'ICT Center | Create Schedule',
        ];
        return view('frontend.staff.pages.course-management.schedule-managment.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'study_day' => 'required|string|max:255',
            'shift' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|string|max:255',
        ]);

        $schedule = new ICTSchedule();
        $schedule->study_day = $request->study_day;
        $schedule->shift = $request->shift;
        $schedule->start_time = $request->start_time;
        $schedule->end_time = $request->end_time;
        $schedule->room = $request->room ?? null;
        $schedule->save();

        return redirect()->route('staff.schedules.index')
            ->with('success', 'Schedule created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $schedule = ICTSchedule::findOrFail($id);
        $data = [
            'page_title' => 'ICT Center | Edit Schedule',
            'schedule' => $schedule,
        ];
        return view('frontend.staff.pages.course-management.schedule-managment.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        // dd($request->all());
        $request->validate([
            'study_day' => 'required|string|max:255',
            'shift' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|string|max:255',
        ]);

        $schedule = ICTSchedule::findOrFail($id);
        $schedule->study_day = $request->study_day;
        $schedule->shift = $request->shift;
        $schedule->start_time = $request->start_time;
        $schedule->end_time = $request->end_time;
        $schedule->room = $request->room ?? null;
        $schedule->save();

        return redirect()->route('staff.schedules.index')
            ->with('success', 'Schedule updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): Response
    {
        try {
            $schedule = ICTSchedule::findOrFail($id);
            $schedule->delete();
            return response([
                'message' => 'Schedule deleted successfully!',
                'status' => 'success',
                'redirect_url' => route('staff.schedules.index'),
            ], 200);
        } catch (Exception $e) {
            logger("Lesson Error >>" . $e);
            return response(['message' => 'Something when wrong!', 500]);
        }
    }
}
