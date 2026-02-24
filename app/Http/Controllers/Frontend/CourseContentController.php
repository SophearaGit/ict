<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Models\CourseChapter;
use App\Models\CourseChapterLesson;
use Exception;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CourseContentController extends Controller
{
    public function createChapterModal(string $course_id): string
    {
        return view('frontend.instructor.pages.course.edit.modals.chapter', compact('course_id'))->render();
    }

    public function storeChapterModal(Request $request, string $course_id): RedirectResponse
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        $chapter = new CourseChapter();
        $chapter->title = $request->title;
        $chapter->course_id = $course_id;
        $chapter->instructor_id = Auth::user()->id;
        $chapter->order = CourseChapter::where('course_id', $course_id)->count() + 1;
        $chapter->save();

        return redirect()->back()
            ->with('success', 'Chapter created successfully!');
    }

    public function editChapterModal(Request $request): string
    {
        $data = [
            'on_edit' => true,
            'course_id' => $request->course_id,
            'chapter_id' => $request->chapter_id,
            'chapter' => CourseChapter::where(column: [
                'instructor_id' => Auth::user()->id,
                'course_id' => $request->course_id,
                'id' => $request->chapter_id
            ])->firstOrFail(),
        ];
        return view('frontend.instructor.pages.course.edit.modals.chapter', $data)->render();
    }

    public function updateChapterModal(Request $request, string $chapter_id): RedirectResponse
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        $chapter = CourseChapter::findOrFail($chapter_id);
        $chapter->title = $request->title;
        $chapter->save();

        return redirect()->back()
            ->with('success', 'Chapter updated successfully!');
    }

    public function deleteChapterModal(string $id): Response
    {
        try {
            $chapter = CourseChapter::findOrFail($id);
            $chapter->delete();
            return response([
                'message' => 'Delete successfully!',
                'status' => 'success'
            ], 200);
        } catch (Exception $e) {
            logger("chapter Error >>" . $e);
            return response(['message' => 'Something when wrong!', 500]);

        }
    }

    public function createLessonModal(Request $request): string
    {
        $data = [
            'course_id' => $request->course_id,
            'chapter_id' => $request->chapter_id,
        ];
        return view('frontend.instructor.pages.course.edit.modals.lesson', $data)->render();
    }

    public function storeLessonModal(Request $request): RedirectResponse
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'storage' => ['required', 'string'],
            'file_type' => ['required', 'in:video,audio,doc,file,pdf'],
            'duration' => ['required'],
            'is_preview' => ['nullable', 'boolean'],
            'downloadable' => ['nullable', 'boolean'],
            'description' => ['required'],
        ];
        if ($request->filled('file_path')) {
            $rules['file_path'] = ['required'];
        } else {
            $rules['url'] = ['required'];
        }

        $request->validate($rules);

        $lesson = new CourseChapterLesson();

        $lesson->instructor_id = Auth::user()->id;
        $lesson->course_id = $request->course_id;
        $lesson->chapter_id = $request->chapter_id;
        $lesson->title = $request->title;
        $lesson->slug = Str::slug($request->title);
        $lesson->storage = $request->storage;
        $lesson->file_path = $request->filled('file_path') ? $request->file_path : $request->url;
        $lesson->file_type = $request->file_type;
        $lesson->duration = $request->duration;
        $lesson->is_preview = $request->has('is_preview') ? 1 : 0;
        $lesson->downloadable = $request->has('downloadable') ? 1 : 0;
        $lesson->order = CourseChapterLesson::where('chapter_id', $request->chapter_id)->count() + 1;
        $lesson->description = $request->description;

        $lesson->save();

        return redirect()->back()
            ->with('success', 'Lesson created successfully!');
    }

    public function editLessonModal(Request $request): string
    {
        $data = [
            'on_edit' => true,
            'course_id' => $request->course_id,
            'chapter_id' => $request->chapter_id,
            'lesson_id' => $request->lesson_id,
            'lesson' => CourseChapterLesson::where(column: [
                'instructor_id' => Auth::user()->id,
                'course_id' => $request->course_id,
                'chapter_id' => $request->chapter_id,
                'id' => $request->lesson_id,
            ])->first(),
        ];
        return view('frontend.instructor.pages.course.edit.modals.lesson', $data)->render();
    }

    public function updateLessonModal(Request $request, string $id): RedirectResponse
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'storage' => ['required', 'string'],
            'file_type' => ['required', 'in:video,audio,doc,file,pdf'],
            'duration' => ['required'],
            'is_preview' => ['nullable', 'boolean'],
            'downloadable' => ['nullable', 'boolean'],
            'description' => ['required'],
        ];
        if ($request->filled('file_path')) {
            $rules['file_path'] = ['required'];
        } else {
            $rules['url'] = ['required'];
        }
        $request->validate($rules);

        $lesson = CourseChapterLesson::findOrFail($id);
        $lesson->instructor_id = Auth::user()->id;
        $lesson->course_id = $request->course_id;
        $lesson->chapter_id = $request->chapter_id;
        $lesson->title = $request->title;
        $lesson->slug = Str::slug($request->title);
        $lesson->storage = $request->storage;
        $lesson->file_path = $request->filled('file_path') ? $request->file_path : $request->url;
        $lesson->file_type = $request->file_type;
        $lesson->duration = $request->duration;
        $lesson->is_preview = $request->has('is_preview') ? 1 : 0;
        $lesson->downloadable = $request->has('downloadable') ? 1 : 0;
        $lesson->description = $request->description;
        $lesson->save();

        return redirect()->back()
            ->with('success', 'Lesson updated successfully!');
    }

    public function deleteLessonModal(string $id): Response
    {
        try {
            $lesson = CourseChapterLesson::findOrFail($id);
            $lesson->delete();
            return response([
                'message' => 'Delete successfully!',
                'status' => 'success'
            ], 200);
        } catch (Exception $e) {
            logger("Lesson Error >>" . $e);
            return response(['message' => 'Something when wrong!', 500]);

        }
    }

    public function sortLesson(Request $request, string $id)
    {
        $new_orders = $request->order_ids;
        foreach ($new_orders as $key => $itemId) {
            $lesson = CourseChapterLesson::where(['chapter_id' => $id, 'id' => $itemId])->first();
            $lesson->order = $key + 1;
            $lesson->save();
        }
        return response([
            "status" => "success",
            "message" => "Updated successfully!",
        ]);
    }
}
