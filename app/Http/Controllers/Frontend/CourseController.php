<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\CourseStoreBasicInfoRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseChapter;
use App\Models\CourseLanguage;
use App\Models\CourseLevel;

use Illuminate\Contracts\View\View;
use App\Traites\FileUpload;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\JsonResponse as HttpFoundationJsonResponse;

class CourseController extends Controller
{
    use FileUpload;

    public function index(): View
    {
        $data = [
            'page_title' => 'ICT Center | Courses',
            'courses' => Course::where('instructor_id', Auth::guard('web')->user()->id)
                ->latest()->paginate(10),
        ];
        return view(('frontend.instructor.pages.course.index'), $data);
    }

    public function create(): View
    {
        $data = [
            'page_title' => 'ICT Center | Create Course',
        ];
        return view(('frontend.instructor.pages.course.create.basic-info'), $data);
    }

    public function storeBasicInfo(CourseStoreBasicInfoRequest $request): JsonResponse
    {

        $thumbnailPath = $this->uploadFile($request->file('thumbnail'));
        $course = new Course();
        $course->title = $request->title;
        $course->slug = Str::slug($request->title);
        $course->seo_description = $request->seo_description;
        $course->thumbnail = $thumbnailPath;
        $course->demo_video_storage = $request->demo_video_storage;
        $course->demo_video_source = $request->filled('filepath') ? $request->filepath : $request->demo_video_source_link;
        $course->price = $request->price;
        $course->discount = $request->discount;
        $course->description = $request->description;
        $course->instructor_id = Auth::guard('web')->user()->id;
        $course->save();

        Session::put('course_create_id', $course->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Basic info saved successfully.',
            'redirect' => route('instructor.courses.edit', ['id' => $course->id, 'step' => $request->next_step])
        ]);
    }

    // editBasicInfo
    public function edit(Request $request): View
    {
        switch ($request->step) {
            case '1':
                $data = [
                    'page_title' => 'ICT Center | Edit Course',
                    'breadcrumb_title' => 'Step 1 | Basic Information 📚',
                    'breadcrumb_sub_title' => 'Give your course basic information like title, description, price, etc.',
                    'step' => $request->step,
                    'course' => Course::findOrFail($request->id),
                ];
                return view('frontend.instructor.pages.course.edit.basic-info', $data);
            case '2':
                $data = [
                    'page_title' => 'ICT Center | Edit Course',
                    'breadcrumb_title' => 'Step 2 | More Information 📝',
                    'breadcrumb_sub_title' => 'Add more details about your course like category, level, language, etc.',
                    'step' => $request->step,
                    'course' => Course::findOrFail($request->id),
                    'categories' => CourseCategory::where('status', 1)->get(),
                    'languages' => CourseLanguage::all(),
                    'levels' => CourseLevel::all(),
                ];
                return view('frontend.instructor.pages.course.edit.more-info', $data);
            case '3':
                $data = [
                    'page_title' => 'ICT Center | Edit Course',
                    'breadcrumb_title' => 'Step 3 | Course Content 🏗️',
                    'breadcrumb_sub_title' => 'Structure your course content by adding chapters and lessons.',
                    'step' => $request->step,
                    'course' => Course::findOrFail($request->id),
                    'course_id' => $request->id,
                    'chapters' => CourseChapter::where(['course_id' => $request->id, 'instructor_id' => Auth::user()->id])
                        ->orderBy('order')->get(),
                ];
                return view('frontend.instructor.pages.course.edit.course-content', $data);
            case '4':
                $data = [
                    'page_title' => 'ICT Center | Edit Course',
                    'breadcrumb_title' => 'Step 4 | Publish Course 🚀',
                    'breadcrumb_sub_title' => 'Set your course status and add a message for the reviewer if needed.',
                    'step' => $request->step,
                    'course_id' => $request->id,
                    'course' => Course::findOrFail($request->id),
                ];
                return view('frontend.instructor.pages.course.edit.finish', $data);
            default:
                $data = [
                    'page_title' => 'ICT Center | Edit Course',
                    'course_id' => $request->id,
                ];
                return view('frontend.instructor.pages.course.create.basic-info', $data);
        }
    }

    public function update(Request $request): HttpFoundationJsonResponse
    {
        switch ($request->current_step) {
            case '1':
                $request->validate([
                    'title' => 'required|string|max:255',
                    'seo_description' => 'nullable|string|max:255',
                    'video_demo_storage' => 'nullable|in:youtube,vimeo,external_link,upload|max:255',
                    'price' => 'required|numeric|min:0',
                    'discount' => 'nullable|numeric|min:0|max:100',
                    'description' => 'required|string',
                    'thumbnail' => 'nullable|image|max:3000',
                    'demo_video_source' => 'nullable',
                ]);
                $course = Course::findOrFail($request->course_id);
                if ($request->hasFile('thumbnail')) {
                    $thumbnailPath = $this->uploadFile($request->file('thumbnail'));
                    $this->deleteIfImageExist($course->thumbnail);
                    $course->thumbnail = $thumbnailPath;
                }
                $course->title = $request->title;
                $course->slug = Str::slug($request->title);
                $course->seo_description = $request->seo_description;
                $course->demo_video_storage = $request->demo_video_storage;
                $course->demo_video_source = $request->filled('filepath') ? $request->filepath : $request->demo_video_source_link;
                $course->price = $request->price;
                $course->discount = $request->discount;
                $course->description = $request->description;
                $course->instructor_id = Auth::guard('web')->user()->id;
                $course->save();

                Session::put('course_create_id', $course->id); // Update session with current course ID

                return response()->json([
                    'status' => 'success',
                    'message' => 'Course basic information updated successfully.',
                    'redirect' => route('instructor.courses.edit', ['id' => $course->id, 'step' => $request->next_step])
                ]);
            case '2':
                $request->validate([
                    'capacity' => 'nullable|numeric',
                    'duration' => 'required|numeric',
                    'qna' => 'nullable|boolean',
                    'certificate' => 'nullable|boolean',
                    'category' => 'required|integer|exists:course_categories,id',
                    'level' => 'required|integer|exists:course_levels,id',
                    'language' => 'required|integer|exists:course_languages,id',
                ]);
                $course = Course::findOrFail($request->course_id);
                $course->capacity = $request->capacity;
                $course->duration = $request->duration;
                $course->qna = $request->qna ? 1 : 0;
                $course->certificate = $request->certificate ? 1 : 0;
                $course->category_id = $request->category;
                $course->course_level_id = $request->level;
                $course->course_language_id = $request->language;
                $course->save();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Course more info updated successfully',
                    'redirect' => route('instructor.courses.edit', ['id' => $course->id, 'step' => $request->next_step])
                ]);
            case '3':
                return response()->json([
                    'status' => 'success',
                    'message' => 'Updated successfully',
                    'redirect' => route('instructor.courses.edit', ['id' => $request->course_id, 'step' => $request->next_step])
                ]);
            case '4':
                $request->validate([
                    'message_for_reviewer' => 'nullable|max:1000|string',
                    'status' => 'required|in:active,inactive,draft'
                ]);
                $course = Course::findOrFail($request->course_id);
                $course->message_for_reviewer = $request->message_for_reviewer;
                $course->status = $request->status;
                $course->save();
                if ($request->submit_for_review_check == 1) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Updated successfully',
                        'redirect' => route('instructor.courses.index')
                    ]);
                } else if ($request->submit_for_review_check == 0) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Updated successfully',
                        'redirect' => route('instructor.courses.edit', ['id' => $course->id, 'step' => $request->next_step])
                    ]);
                }
            default:
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid step'
                ], 400);
        }
    }





}
