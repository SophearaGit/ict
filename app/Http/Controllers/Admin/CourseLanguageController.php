<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseLanguage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CourseLanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            "page_title" => "ICT Center | Course Languages",
            "course_languages" => CourseLanguage::latest()->get(),
        ];
        return view("admin.pages.course-language.index", $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:course_languages',
        ]);

        $courseLanguage = new CourseLanguage();
        $courseLanguage->name = $request->name;
        $courseLanguage->slug = Str::slug($request->name);
        $courseLanguage->save();

        return redirect()->route('admin.course-language.index')
            ->with('success', 'language created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:course_languages,name,' . $id,
        ]);

        $courseLanguage = CourseLanguage::findOrFail($id);
        $courseLanguage->name = $request->name;
        $courseLanguage->save();

        return redirect()->route('admin.course-language.index')
            ->with('success', 'language updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseLanguage $course_language): RedirectResponse
    {
        try {
            $course_language->delete();
            return redirect()->route('admin.course-language.index')
                ->with('success', 'language deleted successfully.');
        } catch (Exception $e) {
            logger($e);
            return redirect()->route('admin.course-language.index')
                ->with('error', 'Failed to delete language. Please try again.');
        }
    }
}
