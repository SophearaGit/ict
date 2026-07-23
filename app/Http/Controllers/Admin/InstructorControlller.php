<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ICTCourse;
use App\Traites\FileUpload;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InstructorControlller extends Controller
{
    use FileUpload;

    public function index(Request $request): View
    {
        $instructors = User::query()
            ->where('role', 'instructor')
            ->where('approval_status', 'approved')
            ->whereNotNull('document')
            ->with([
                'courses.students',
            ])
            ->withCount([
                'courses',
                'reports',
            ])
            // Sum of actual teaching hours logged (present sessions only)
            ->withSum(['attendances as total_actual_hours' => function ($q) {
                $q->where('status', 'present');
            }], 'actual_hours')
            // Search
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%")
                        ->orWhere('email', 'like', "%{$request->search}%");
                });
            })
            // Subject
            ->when(
                $request->filled('subject') &&
                $request->subject !== 'All Subject',
                function ($query) use ($request) {
                    $query->whereHas('courses', function ($q) use ($request) {
                        $q->where('title', $request->subject);
                    });
                }
            )
            // Status
            ->when(
                $request->filled('status') &&
                $request->status !== 'All Status',
                function ($query) use ($request) {
                    $query->where('status', $request->status);
                }
            )
            // Gender
            ->when(
                $request->filled('gender') &&
                $request->gender !== 'All Gender',
                function ($query) use ($request) {
                    $query->where('gender', $request->gender);
                }
            )
            ->latest()
            ->paginate(12)
            ->withQueryString();

        // Calculate unique students for each instructor
        $instructors->getCollection()->transform(function ($teacher) {
            $teacher->student_count = $teacher->courses
                ->flatMap(function ($course) {
                    return $course->students;
                })
                ->unique('id')
                ->count();
            return $teacher;
        });

        $subjects = ICTCourse::query()
            ->orderBy('title')
            ->pluck('title')
            ->unique()
            ->values();

        return view('admin.pages.user.instructor', [
            'page_title' => 'ICT | ADMIN | INSTRUCTORS',
            'instructors' => $instructors,
            'subjects' => $subjects,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'document' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:12000',
        ]);

        if ($request->hasFile('document')) {
            $filePath = $this->uploadFile($request->file('document'));
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'instructor',
            'approval_status' => 'approved',
            'status' => 'active',
            'document' => $filePath,
        ]);

        return redirect()->route('admin.instructor.index')
            ->with('success', 'Instructor created successfully.');
    }

    public function instructorShowDetail($id): View
    {
        $instructor = User::query()
            ->with('courses.students')
            ->withCount(['courses', 'reports'])
            ->withSum(['attendances as total_actual_hours' => function ($q) {
                $q->where('status', 'present');
            }], 'actual_hours')
            ->findOrFail($id);

        $instructor->student_count = $instructor->courses
            ->flatMap(function ($course) {
                return $course->students;
            })
            ->unique('id')
            ->count();

        // ✅ Get latest ATH per course (NOT SUM)
        $athByCourse = DB::table('teacher_attendances as t1')
            ->select('t1.course_id', 't1.actual_hours as ath')
            ->where('t1.teacher_id', $id)
            ->where('t1.status', 'present')
            ->whereRaw('t1.id = (
            SELECT MAX(t2.id)
            FROM teacher_attendances t2
            WHERE t2.course_id = t1.course_id
            AND t2.teacher_id = t1.teacher_id
        )')
            ->get()
            ->keyBy('course_id');

        return view('admin.pages.user.instructor-detail', [
            'instructor' => $instructor,
            'athByCourse' => $athByCourse,
        ]);
    }

    /**
     * Flip an instructor between Active / On_Leave.
     * Mirrors the intern "enable / disable" toggle, but uses the
     * `status` column instead of `role` since `role` is relied on
     * elsewhere (course ownership, policies, etc.).
     */
    public function toggle(User $instructor): RedirectResponse|JsonResponse
    {
        $instructor->status = $instructor->status === 'active' ? 'on_leave' : 'active';
        $instructor->save();

        if (request()->wantsJson()) {
            return response()->json([
                'message' => 'Instructor status updated.',
                'status' => $instructor->status,
            ]);
        }

        return back()->with('success', 'Instructor status updated.');
    }

    /**
     * Remove an instructor. Blocks deletion if they still have
     * active courses, so admin doesn't accidentally orphan course data.
     */
    public function destroy(User $instructor): JsonResponse
    {
        if ($instructor->courses()->exists()) {
            return response()->json([
                'message' => 'Cannot remove an instructor who still has assigned courses. Reassign their courses first.',
            ], 422);
        }

        $instructor->delete();

        return response()->json([
            'message' => 'Instructor removed successfully.',
        ]);
    }
}
