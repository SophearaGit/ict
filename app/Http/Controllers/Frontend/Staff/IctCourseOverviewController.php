<?php

namespace App\Http\Controllers\Frontend\Staff;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\Staff\Concerns\SyncsCourseBulletPoints;
use App\Models\ICTCourse;
use App\Models\ICTCourseLearningPoint;
use App\Models\ICTCourseRequirement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IctCourseOverviewController extends Controller
{
    use SyncsCourseBulletPoints;

    /**
     * List all courses so staff can pick which one to edit the
     * "What you'll learn" / "Requirements" overview content for.
     */
    public function index(Request $request): View
    {
        $courses = ICTCourse::query()
            ->withCount(['learningPoints', 'requirements'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->string('search') . '%');
            })
            ->orderBy('title')
            ->paginate(15)
            ->withQueryString();

        return view('frontend.staff.pages.course-overview.index', [
            'page_title' => 'Course Overview',
            'courses' => $courses,
        ]);
    }

    /**
     * Show the editor for a single course's learning points & requirements.
     */
    public function show(ICTCourse $course): View
    {
        $course->load(['learningPoints', 'requirements']);

        return view('frontend.staff.pages.course-overview.show', [
            'page_title' => 'Edit Overview — ' . $course->title,
            'course' => $course,
        ]);
    }

    /**
     * Persist both lists for the course in one submit.
     */
    public function update(Request $request, ICTCourse $course): RedirectResponse
    {
        $validated = $request->validate([
            'learning_points' => ['nullable', 'array'],
            'learning_points.*' => ['nullable', 'string', 'max:255'],
            'requirements' => ['nullable', 'array'],
            'requirements.*' => ['nullable', 'string', 'max:255'],
        ]);

        $this->syncCourseBulletPoints($course, ICTCourseLearningPoint::class, $validated['learning_points'] ?? []);
        $this->syncCourseBulletPoints($course, ICTCourseRequirement::class, $validated['requirements'] ?? []);

        return redirect()
            ->route('staff.courses.edit', $course)
            ->with('success', 'Course overview updated.');
    }
}
