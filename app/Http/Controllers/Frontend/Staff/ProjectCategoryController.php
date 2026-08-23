<?php

namespace App\Http\Controllers\Frontend\Staff;

use App\Http\Controllers\Controller;
use App\Models\ProjectCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectCategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = ProjectCategory::withCount('projects')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('is_active', $request->status === 'active');
            })
            ->orderBy('sort_order')
            ->paginate(15)
            ->withQueryString();

        return view('frontend.staff.pages.projects.categories.index', [
            'page_title' => 'ICT | Project Categories',
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateCategory($request);
        $data['slug'] = $this->uniqueSlug($data['name']);

        ProjectCategory::create($data);

        return redirect()
            ->route('staff.project-categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function update(Request $request, ProjectCategory $projectCategory)
    {
        $data = $this->validateCategory($request, $projectCategory->id);

        if ($data['name'] !== $projectCategory->name) {
            $data['slug'] = $this->uniqueSlug($data['name'], $projectCategory->id);
        }

        $projectCategory->update($data);

        return redirect()
            ->route('staff.project-categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(ProjectCategory $projectCategory)
    {
        $projectCategory->delete();

        return redirect()
            ->route('staff.project-categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    private function validateCategory(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $count = 1;

        while (
            ProjectCategory::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$original}-{$count}";
            $count++;
        }

        return $slug;
    }
}
