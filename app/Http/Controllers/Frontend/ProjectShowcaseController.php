<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\Http\Request;

class ProjectShowcaseController extends Controller
{
    /**
     * GET /projects — the public Project Showcase page (hero spotlight, filter
     * pills, search, and the card grid). The detail view opens in-page as a
     * modal, populated via AJAX from show().
     */
    public function index(Request $request)
    {
        $categories = ProjectCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $projects = Project::published()
            ->with(['category', 'student', 'technologies'])
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%');
            })
            ->latest('published_at')
            ->paginate(6)
            ->withQueryString();

        $spotlight = Project::published()
            ->with(['category', 'student', 'instructor', 'technologies'])
            ->featured()
            ->latest('published_at')
            ->first()
            ?? Project::published()
                ->with(['category', 'student', 'instructor', 'technologies'])
                ->latest('published_at')
                ->first();

        return view('frontend.pages.home-new.project', [
            'page_title' => 'Project Showcase',
            'projects' => $projects,
            'categories' => $categories,
            'spotlight' => $spotlight,
        ]);
    }

    /**
     * GET /projects/{slug}
     *
     * - AJAX (Accept: application/json) → JSON payload consumed by the modal
     *   JS on the index page, and increments the view counter.
     * - Direct navigation (e.g. a shared link) → redirect back to the index
     *   page with ?open={slug} so the page loads and then auto-opens the
     *   modal via the same JSON fetch, keeping this deep-linkable without a
     *   separate full detail page.
     */
    public function show(Request $request, string $slug)
    {
        $project = Project::published()->where('slug', $slug)->firstOrFail();

        if (! $request->wantsJson() && ! $request->ajax()) {
            return redirect()->route('projects', ['open' => $project->slug]);
        }

        $project->incrementViews();
        $project->load(['category', 'student', 'instructor', 'technologies', 'objectives', 'processSteps', 'screenshots']);

        return response()->json([
            'slug' => $project->slug,
            'title' => $project->title,
            'category' => $project->category->name ?? null,
            'featured_label' => $project->is_featured ? ($project->featured_label ?: 'Featured') : null,
            'published_date' => optional($project->published_at)->format('F Y'),
            'time_ago' => optional($project->published_at)->diffForHumans(null, true),
            'views' => $project->views,
            'likes' => $project->likes,
            'live_demo_url' => $project->live_demo_url,
            'github_url' => $project->github_url,
            'documentation_url' => $project->documentation_url,
            'overview' => $project->overview,
            'problem_statement' => $project->problem_statement,
            'challenges' => $project->challenges,
            'solutions' => $project->solutions,
            'objectives' => $project->objectives->pluck('content')->all(),
            'technologies' => $project->technologies->pluck('name')->all(),
            'process_steps' => $project->processSteps->map(fn ($step) => [
                'step_number' => $step->step_number,
                'title' => $step->title,
                'description' => $step->description,
            ])->all(),
            'screenshots' => $project->screenshots->pluck('image_url')->filter()->values()->all(),
            'thumbnail_url' => $project->thumbnail_url,
            'cover_image_url' => $project->cover_image_url,
            'batch_label' => $project->batch_label,
            'student' => $project->student ? [
                'name' => $project->student->name,
                // Adjust to your actual avatar column/accessor if this differs.
                'avatar_url' => $project->student->avatar_url ?? null,
            ] : null,
            'instructor' => $project->instructor->name ?? null,
        ]);
    }
}
