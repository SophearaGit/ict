<?php

namespace App\Http\Controllers\Frontend\Staff;

use App\Http\Controllers\Controller;
use App\Models\ICTCourseCategory;
use App\Traites\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IctCourseCategoryController extends Controller
{
    use FileUpload;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ICTCourseCategory::with('parent')->withoutTrashed();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $categories = $query->orderBy('sort_order')->orderBy('name')->paginate(12)->withQueryString();
        $parentCategories = ICTCourseCategory::whereNull('parent_id')->orderBy('name')->get();

        return view('frontend.staff.pages.course-categories.index', [
            'page_title' => 'ICT | STAFF | COURSE CATEGORIES',
            'categories' => $categories,
            'parentCategories' => $parentCategories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * (Not used — handled via modal on index)
     */
    public function create()
    {
        return redirect()->route('staff.course-categories.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:i_c_t_course_categories,slug',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'parent_id' => 'nullable|exists:i_c_t_course_categories,id',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        // Slug fallback
        $validated['slug'] = $validated['slug'] ? Str::slug($validated['slug']) : Str::slug($validated['name']);

        // Ensure unique slug
        $validated['slug'] = $this->uniqueSlug($validated['slug']);

        // Booleans
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');

        // Thumbnail upload
        $validated['thumbnail'] = null;
        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $this->uploadFile($request->file('thumbnail'), 'uploads/course-categories/thumbnails');
        }

        $validated['created_by'] = auth()->id();

        ICTCourseCategory::create($validated);

        return redirect()->route('staff.course-categories.index')->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     * (Not used — no dedicated show page)
     */
    public function show(string $id)
    {
        return redirect()->route('staff.course-categories.index');
    }

    /**
     * Show the form for editing the specified resource.
     * (Not used — handled via modal on index)
     */
    public function edit(string $id)
    {
        return redirect()->route('staff.course-categories.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = ICTCourseCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => "nullable|string|max:255|unique:i_c_t_course_categories,slug,{$id}",
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'parent_id' => 'nullable|exists:i_c_t_course_categories,id',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        // Prevent self-referencing parent
        if (!empty($validated['parent_id']) && $validated['parent_id'] == $id) {
            return back()->withErrors(['parent_id' => 'A category cannot be its own parent.']);
        }

        // Slug
        $validated['slug'] = $validated['slug'] ? Str::slug($validated['slug']) : Str::slug($validated['name']);

        // Booleans
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');

        // Thumbnail upload — delete old one if replaced
        if ($request->hasFile('thumbnail')) {
            if ($category->thumbnail) {
                $this->deleteIfImageExist($category->thumbnail);
            }
            $validated['thumbnail'] = $this->uploadFile($request->file('thumbnail'), 'uploads/course-categories/thumbnails');
        }

        $validated['updated_by'] = auth()->id();

        $category->update($validated);

        return redirect()->route('staff.course-categories.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = ICTCourseCategory::findOrFail($id);

        // Reassign children to top level before soft-deleting
        ICTCourseCategory::where('parent_id', $id)->update(['parent_id' => null]);

        $category->delete();

        return redirect()->route('staff.course-categories.index')->with('success', 'Category deleted successfully.');
    }

    /*──────────────────────────────────────────────
     | Helpers
     ──────────────────────────────────────────────*/

    /**
     * Append a numeric suffix if the slug already exists.
     */
    private function uniqueSlug(string $slug, ?int $exceptId = null): string
    {
        $original = $slug;
        $counter = 1;

        while (ICTCourseCategory::where('slug', $slug)->when($exceptId, fn($q) => $q->where('id', '!=', $exceptId))->exists()) {
            $slug = "{$original}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
