<?php

namespace App\Http\Controllers\Frontend\Staff;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\ProjectObjective;
use App\Models\ProjectProcessStep;
use App\Models\ProjectScreenshot;
use App\Models\ProjectTechnology;
use App\Models\User;
use App\Traites\FileUpload;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    use FileUpload;

    /**
     * Where uploaded project images are stored (public/uploads/projects/...).
     */
    private const THUMBNAIL_PATH = 'uploads/projects/thumbnail';
    private const COVER_PATH = 'uploads/projects/cover';
    private const SCREENSHOT_PATH = 'uploads/projects/screenshots';

    public function index(Request $request)
    {
        $projects = Project::query()
            ->with(['category', 'student', 'instructor'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('category_id'), function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = ProjectCategory::where('is_active', true)->orderBy('sort_order')->get();

        return view('frontend.staff.pages.projects.index', [
            'page_title' => 'ICT | Projects',
            'projects' => $projects,
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        return view('frontend.staff.pages.projects.create', [
            'page_title' => 'ICT | Add Project',
            'categories' => ProjectCategory::where('is_active', true)->orderBy('sort_order')->get(),
            'students' => User::where('role', 'student')->orderBy('name')->get(),
            'instructors' => User::where('role', 'instructor')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateProject($request);

        $data['thumbnail'] = $this->storeImage($request, 'thumbnail', self::THUMBNAIL_PATH);
        $data['cover_image'] = $this->storeImage($request, 'cover_image', self::COVER_PATH);

        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $project = Project::create($data);

        $this->syncTechnologies($project, $request->input('technologies', []));
        $this->syncObjectives($project, $request->input('objectives', []));
        $this->syncProcessSteps($project, $request->input('process_steps', []));
        $this->storeScreenshots($project, $request);

        return redirect()
            ->route('staff.projects.index')
            ->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        $project->load(['category', 'student', 'instructor', 'technologies', 'objectives', 'processSteps', 'screenshots']);

        return view('frontend.staff.pages.projects.show', [
            'page_title' => 'ICT | ' . $project->title,
            'project' => $project,
        ]);
    }

    public function edit(Project $project)
    {
        $project->load(['technologies', 'objectives', 'processSteps', 'screenshots']);

        return view('frontend.staff.pages.projects.edit', [
            'page_title' => 'ICT | Edit Project',
            'project' => $project,
            'categories' => ProjectCategory::where('is_active', true)->orderBy('sort_order')->get(),
            'students' => User::where('role', 'student')->orderBy('name')->get(),
            'instructors' => User::where('role', 'instructor')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Project $project)
    {
        $data = $this->validateProject($request, $project->id);

        if ($request->hasFile('thumbnail')) {
            $this->deleteImage($project->thumbnail);
            $data['thumbnail'] = $this->storeImage($request, 'thumbnail', self::THUMBNAIL_PATH);
        }

        if ($request->hasFile('cover_image')) {
            $this->deleteImage($project->cover_image);
            $data['cover_image'] = $this->storeImage($request, 'cover_image', self::COVER_PATH);
        }

        if ($data['status'] === 'published' && empty($project->published_at) && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $project->update($data);

        $this->syncTechnologies($project, $request->input('technologies', []));
        $this->syncObjectives($project, $request->input('objectives', []));
        $this->syncProcessSteps($project, $request->input('process_steps', []));

        // Remove screenshots the staff explicitly unchecked, then append any new uploads.
        if ($request->filled('remove_screenshots')) {
            $toRemove = ProjectScreenshot::where('project_id', $project->id)
                ->whereIn('id', $request->input('remove_screenshots', []))
                ->get();
            foreach ($toRemove as $screenshot) {
                $this->deleteImage($screenshot->image_path);
                $screenshot->delete();
            }
        }
        $this->storeScreenshots($project, $request);

        return redirect()
            ->route('staff.projects.index')
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $this->deleteImage($project->thumbnail);
        $this->deleteImage($project->cover_image);
        foreach ($project->screenshots as $screenshot) {
            $this->deleteImage($screenshot->image_path);
        }

        $project->delete();

        return redirect()
            ->route('staff.projects.index')
            ->with('success', 'Project deleted successfully.');
    }

    public function toggleFeatured(Project $project)
    {
        $project->update(['is_featured' => ! $project->is_featured]);

        return back()->with('success', $project->is_featured
            ? 'Project marked as featured.'
            : 'Project removed from featured.');
    }

    // ---------------------------------------------------------------
    // Helpers
    // ---------------------------------------------------------------

    private function validateProject(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'category_id' => ['nullable', 'exists:project_categories,id'],
            'student_id' => ['nullable', 'exists:users,id'],
            'instructor_id' => ['nullable', 'exists:users,id'],
            'batch_label' => ['nullable', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'thumbnail' => ['nullable', 'image', 'max:4096'],
            'cover_image' => ['nullable', 'image', 'max:4096'],
            'overview' => ['nullable', 'string'],
            'problem_statement' => ['nullable', 'string'],
            'challenges' => ['nullable', 'string'],
            'solutions' => ['nullable', 'string'],
            'live_demo_url' => ['nullable', 'url', 'max:255'],
            'github_url' => ['nullable', 'url', 'max:255'],
            'documentation_url' => ['nullable', 'url', 'max:255'],
            'build_duration' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'in:draft,published'],
            'is_featured' => ['nullable', 'boolean'],
            'featured_label' => ['nullable', 'string', 'max:50'],
            'published_at' => ['nullable', 'date'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],

            'technologies' => ['nullable', 'array'],
            'technologies.*' => ['nullable', 'string', 'max:100'],

            'objectives' => ['nullable', 'array'],
            'objectives.*' => ['nullable', 'string', 'max:255'],

            'process_steps' => ['nullable', 'array'],
            'process_steps.*.title' => ['nullable', 'string', 'max:100'],
            'process_steps.*.description' => ['nullable', 'string'],

            'screenshots' => ['nullable', 'array'],
            'screenshots.*' => ['nullable', 'image', 'max:4096'],
        ]);

        $data['is_featured'] = $request->boolean('is_featured');

        return $data;
    }

    private function storeImage(Request $request, string $field, string $directory): ?string
    {
        if (! $request->hasFile($field)) {
            return null;
        }

        return $this->uploadFile($request->file($field), $directory);
    }

    private function deleteImage(?string $path): void
    {
        $this->deleteIfImageExist($path);
    }

    private function syncTechnologies(Project $project, array $technologies): void
    {
        ProjectTechnology::where('project_id', $project->id)->delete();

        foreach (array_values(array_filter($technologies)) as $index => $name) {
            ProjectTechnology::create([
                'project_id' => $project->id,
                'name' => $name,
                'order' => $index,
            ]);
        }
    }

    private function syncObjectives(Project $project, array $objectives): void
    {
        ProjectObjective::where('project_id', $project->id)->delete();

        foreach (array_values(array_filter($objectives)) as $index => $content) {
            ProjectObjective::create([
                'project_id' => $project->id,
                'content' => $content,
                'order' => $index,
            ]);
        }
    }

    private function syncProcessSteps(Project $project, array $steps): void
    {
        ProjectProcessStep::where('project_id', $project->id)->delete();

        $index = 0;
        foreach ($steps as $step) {
            if (empty($step['title'])) {
                continue;
            }
            $index++;
            ProjectProcessStep::create([
                'project_id' => $project->id,
                'step_number' => $index,
                'title' => $step['title'],
                'description' => $step['description'] ?? null,
                'order' => $index,
            ]);
        }
    }

    private function storeScreenshots(Project $project, Request $request): void
    {
        if (! $request->hasFile('screenshots')) {
            return;
        }

        $startOrder = ProjectScreenshot::where('project_id', $project->id)->max('order') + 1;

        foreach ($request->file('screenshots') as $index => $file) {
            $path = $this->uploadFile($file, self::SCREENSHOT_PATH);

            ProjectScreenshot::create([
                'project_id' => $project->id,
                'image_path' => $path,
                'order' => $startOrder + $index,
            ]);
        }
    }
}
