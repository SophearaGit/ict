<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseLevel;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = [
            "page_title" => "ICT CENTER | Course Levels",
            "course_levels" => CourseLevel::
                when($request->filled('search'), function ($query) use ($request) {
                    $query->where('name', 'like', "%{$request->search}%");
                })->
                latest()->paginate(10),
        ];
        return view("admin.pages.course-level.index", $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:course_levels',
        ]);

        $courseLevel = new CourseLevel();
        $courseLevel->name = $request->name;
        $courseLevel->slug = Str::slug($request->name);
        $courseLevel->save();

        return redirect()->route('admin.course-level.index')
            ->with('success', 'Level created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:course_levels,name,' . $id,
        ]);

        $courseLevel = CourseLevel::findOrFail($id);
        $courseLevel->name = $request->name;
        $courseLevel->slug = Str::slug($request->name);
        $courseLevel->save();

        return redirect()->route('admin.course-level.index')
            ->with('success', 'Level updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseLevel $course_level): RedirectResponse
    {
        try {
            $course_level->delete();
            return redirect()->route('admin.course-level.index')
                ->with('success', 'Level deleted successfully.');
        } catch (Exception $e) {
            logger($e);
            return redirect()->route('admin.course-level.index')
                ->with('error', 'Failed to delete level. Please try again.');
        }
    }
}
