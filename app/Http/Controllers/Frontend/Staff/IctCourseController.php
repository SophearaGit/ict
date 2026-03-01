<?php

namespace App\Http\Controllers\Frontend\Staff;

use App\Http\Controllers\Controller;
use App\Models\ICTCourse;
use App\Models\ICTSchedule;
use App\Models\User;
use App\Traites\FileUpload;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\RedirectResponse;

class IctCourseController extends Controller
{

    use FileUpload;

    public function index(): View
    {
        $data = [
            'page_title' => 'ICT Center | Dashboard',
            'courses' => ICTCourse::latest()->paginate(10),
        ];
        return view('frontend.staff.pages.course-management.course', $data);
    }

    public function create(): View
    {
        $data = [
            'page_title' => 'ICT Center | Create Course',
            'instructors' => User::where('role', 'instructor')
                ->latest()->paginate(10),
            'schedules' => ICTSchedule::latest()->get()->groupBy('study_day'),
        ];
        return view('frontend.staff.pages.course-management.create', $data);
    }

    public function store(Request $request): RedirectResponse
    {
        // dd($request->all());
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
            'instructor_id' => 'required|exists:users,id',
            'schedule_id' => 'required|exists:i_c_t_schedules,id',
            'description' => 'required|string',
            'thumbnail' => 'nullable|image|max:3000',
        ]);

        if ($request->hasFile('thumbnail')) {
             $thumbnailPath = $this->uploadFile($request->file('thumbnail'));
        } else {
            $thumbnailPath = null; // or set a default thumbnail path if needed
        }

        // $thumbnailPath = $this->uploadFile($request->file('thumbnail'));

        $course = new ICTCourse();
        $course->title = $request->title;
        $course->price = $request->price;
        $course->slug = Str::slug($request->title);
        $course->thumbnail = $thumbnailPath;
        $course->start_date = $request->start_date;
        $course->end_date = $request->end_date;
        $course->status = $request->status;
        $course->instructor_id = $request->instructor_id;
        $course->schedule_id = $request->schedule_id;
        $course->description = $request->description;
        $course->save();

        return redirect()->route('staff.courses.index')
            ->with('success', 'Course created successfully. Please add lessons to the course.');
    }

    public function edit($id): View
    {
        $data = [
            'page_title' => 'ICT Center | Edit Course',
            // 'course' => Course::findOrFail($id), // Fetch the course data based on ID
        ];
        return view('frontend.staff.pages.course-management.edit', $data);
    }

    public function update(Request $request, $id)
    {
        // Validate and update the course data based on ID
        // Redirect or return response as needed
    }



}
