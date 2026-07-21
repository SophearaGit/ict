<?php
namespace App\Http\Controllers\Frontend\Staff;
use App\Http\Controllers\Controller;
use App\Models\ICTCourse;
use App\Models\ICTCourseChapter;
use App\Models\ICTCourseChapterLesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
class IctCourseCurriculumController extends Controller
{
    public function index(Request $request): View
    {
        $courses = ICTCourse::with('instructor')
            ->withCount([
                'chapters',
                'lessons',
            ])
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%');
            })
            ->orderBy('title')
            ->paginate(12)
            ->withQueryString();

        return view('frontend.staff.pages.course-management.curriculum-index', [
            'page_title' => 'ICT | STAFF | CURRICULUM',
            'courses' => $courses,
        ]);
    }
    /**
     * Render the curriculum body (chapters + lessons) for a course.
     * Reused after every mutation so the modal always reflects the DB.
     */
    protected function renderCurriculum(ICTCourse $course): string
    {
        $course->load('chapters.lessons');
        return view('frontend.staff.pages.course-management.partials.curriculum-body', [
            'course' => $course,
        ])->render();
    }
    public function show(ICTCourse $course): JsonResponse
    {
        return response()->json([
            'success' => true,
            'html' => $this->renderCurriculum($course),
        ]);
    }
    /*******************************************************
     * CHAPTERS
     *******************************************************/
    public function storeChapter(Request $request, ICTCourse $course): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);
        $maxOrder = ICTCourseChapter::where('course_id', $course->id)->max('order');
        ICTCourseChapter::create([
            'course_id' => $course->id,
            'title' => $request->title,
            'order' => $maxOrder !== null ? $maxOrder + 1 : 0,
            'status' => true,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Chapter added successfully.',
            'html' => $this->renderCurriculum($course),
        ]);
    }
    public function updateChapter(Request $request, ICTCourseChapter $chapter): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);
        $chapter->update(['title' => $request->title]);
        return response()->json([
            'success' => true,
            'message' => 'Chapter updated successfully.',
            'html' => $this->renderCurriculum($chapter->course),
        ]);
    }
    public function destroyChapter(ICTCourseChapter $chapter): JsonResponse
    {
        $course = $chapter->course;
        $chapter->delete(); // lessons cascade via FK
        return response()->json([
            'success' => true,
            'message' => 'Chapter deleted successfully.',
            'html' => $this->renderCurriculum($course),
        ]);
    }
    public function reorderChapters(Request $request, ICTCourse $course): JsonResponse
    {
        $request->validate([
            'order' => 'required|array|min:1',
            'order.*' => 'integer|exists:i_c_t_course_chapters,id',
        ]);
        DB::transaction(function () use ($request, $course) {
            foreach ($request->order as $index => $chapterId) {
                ICTCourseChapter::where('id', $chapterId)
                    ->where('course_id', $course->id)
                    ->update(['order' => $index]);
            }
        });
        return response()->json(['success' => true]);
    }
    /*******************************************************
     * LESSONS
     *******************************************************/
    public function storeLesson(Request $request, ICTCourseChapter $chapter): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);
        $maxOrder = ICTCourseChapterLesson::where('chapter_id', $chapter->id)->max('order');
        ICTCourseChapterLesson::create([
            'chapter_id' => $chapter->id,
            'course_id' => $chapter->course_id,
            'title' => $request->title,
            'order' => $maxOrder !== null ? $maxOrder + 1 : 0,
            'status' => true,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Lesson added successfully.',
            'html' => $this->renderCurriculum($chapter->course),
        ]);
    }
    public function updateLesson(Request $request, ICTCourseChapterLesson $lesson): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);
        $lesson->update(['title' => $request->title]);
        return response()->json([
            'success' => true,
            'message' => 'Lesson updated successfully.',
            'html' => $this->renderCurriculum($lesson->course),
        ]);
    }
    public function destroyLesson(ICTCourseChapterLesson $lesson): JsonResponse
    {
        $course = $lesson->course;
        $lesson->delete();
        return response()->json([
            'success' => true,
            'message' => 'Lesson deleted successfully.',
            'html' => $this->renderCurriculum($course),
        ]);
    }
    public function reorderLessons(Request $request, ICTCourseChapter $chapter): JsonResponse
    {
        $request->validate([
            'order' => 'required|array|min:1',
            'order.*' => 'integer|exists:i_c_t_course_chapter_lessons,id',
        ]);
        DB::transaction(function () use ($request, $chapter) {
            foreach ($request->order as $index => $lessonId) {
                ICTCourseChapterLesson::where('id', $lessonId)
                    ->where('chapter_id', $chapter->id)
                    ->update(['order' => $index]);
            }
        });
        return response()->json(['success' => true]);
    }
}
