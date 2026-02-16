<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\CourseCategoryStoreRequest;
use App\Http\Requests\Admin\CourseCategoryUpdateRequest;
use App\Models\CourseCategory;
use App\Traites\FileUpload;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CourseCategoryController extends Controller
{

    use FileUpload;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = [
            'page_title' => 'ICT Center | Course Categories',
            'course_categories' => CourseCategory::
                when($request->has('search') && $request->filled('search'), function ($query) use ($request) {
                    $query->where('name', 'like', "%{$request->search}%");
                })->
                whereNull('parent_id')->
                latest()->paginate(10),
            'course_categories_parent_for_select' => CourseCategory::whereNull('parent_id')->latest()->get(),

        ];
        return view('admin.pages.course-category.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourseCategoryStoreRequest $request): RedirectResponse
    {
        $category = new CourseCategory();
        if ($request->hasFile('image')) {
            $imagePath = $this->uploadFile($request->file('image'));
            $category->image = $imagePath;
        }
        $category->parent_id = $request->parent_id ?? null;
        $category->name = $request->name;
        $category->icon = $request->icon;
        $category->slug = Str::slug($request->name);
        $category->show_at_trending = $request->show_at_trending ?? 0;
        $category->status = $request->status ?? 0;
        $category->save();
        return redirect()->route('admin.course-category.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CourseCategoryUpdateRequest $request, CourseCategory $course_category): RedirectResponse
    {
        // dd($request->all(), $course_category);
        $category = $course_category;

        if ($request->hasFile('image')) {
            $imagePath = $this->uploadFile($request->file('image'));
            $this->deleteIfImageExist($category->image);
            $category->image = $imagePath;
        }

        $category->parent_id = $request->parent_id ?? null;
        $category->name = $request->name;
        $category->icon = $request->icon;
        $category->slug = Str::slug($request->name);
        $category->show_at_trending = $request->show_at_trending ?? 0;
        $category->status = $request->status ?? 0;
        $category->save();

        return redirect()->route('admin.course-category.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseCategory $course_category): RedirectResponse
    {

        if (CourseCategory::where('parent_id', $course_category->id)->exists()) {
            return redirect()->route('admin.course-category.index')
                ->with('error', 'Cannot delete category with subcategories. Please delete subcategories first.');
        }

        try {
            if ($course_category->image != '') {
                $this->deleteIfImageExist($course_category->image);
            }
            $course_category->delete();
            return redirect()->route('admin.course-category.index')
                ->with('success', 'Category deleted successfully.');
        } catch (\Exception $e) {
            logger($e);
            return redirect()->route('admin.course-category.index')
                ->with('error', 'Failed to delete category. Please try again.');
        }


    }
}
