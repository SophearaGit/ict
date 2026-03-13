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

    public function index(Request $request): View
    {
        $perPage = $request->input('per_page', 10);

        $courses = ICTCourse::when($request->filled('search'), function ($query) use ($request) {
            $query->where('title', 'like', '%' . $request->search . '%');
        })
            ->orderBy('title', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return view('frontend.staff.pages.course-management.course', [
            'page_title' => 'ICT | Staff | Courses',
            'courses' => $courses,
        ]);
    }

    public function create(): View
    {
        $data = [
            'page_title' => 'ICT | Staff | Create Course',
            'instructors' => User::where('role', 'instructor')
                ->latest()->paginate(10),
            'schedules' => ICTSchedule::latest()->get()->groupBy('study_day'),
        ];
        return view('frontend.staff.pages.course-management.create', $data);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'instructor_id' => 'required|exists:users,id',
            'schedule_id' => 'required|exists:i_c_t_schedules,id',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:3000',
        ]);

        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $this->uploadFile($request->file('thumbnail'));
        } else {
            $thumbnailPath = ""; // or set a default thumbnail path if needed
        }

        $course = new ICTCourse();
        $course->title = $request->title;
        $course->price = $request->price;
        $course->slug = Str::slug($request->title);
        $course->thumbnail = $thumbnailPath;
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
            'page_title' => 'ICT | Staff | Edit Course',
            'course' => ICTCourse::findOrFail($id),
            'instructors' => User::where('role', 'instructor')
                ->latest()->paginate(10),
            'schedules' => ICTSchedule::latest()->get()->groupBy('study_day'),
        ];
        return view('frontend.staff.pages.course-management.edit', $data);
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $course = ICTCourse::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'instructor_id' => 'required|exists:users,id',
            'schedule_id' => 'required|exists:i_c_t_schedules,id',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:3000',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail != '') {
                $this->deleteIfImageExist($course->thumbnail);
            }
            $thumbnailPath = $this->uploadFile($request->file('thumbnail'));
        } else {
            $thumbnailPath = $course->thumbnail; // Keep existing thumbnail if no new file is uploaded
        }

        $course->title = $request->title;
        $course->price = $request->price;
        $course->slug = Str::slug($request->title);
        $course->thumbnail = $thumbnailPath;
        $course->status = $request->status;
        $course->instructor_id = $request->instructor_id;
        $course->schedule_id = $request->schedule_id;
        $course->description = $request->description;
        $course->save();

        return redirect()->route('staff.courses.index')
            ->with('success', 'Course updated successfully.');

    }



}
