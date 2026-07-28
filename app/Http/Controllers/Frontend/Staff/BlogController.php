<?php

namespace App\Http\Controllers\Frontend\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreBlogRequest;
use App\Http\Requests\Staff\UpdateBlogRequest;
use App\Models\Blog;
use App\Traites\FileUpload;
use App\Traites\HandlesBlogThumbnails;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BlogController extends Controller
{
    use FileUpload, HandlesBlogThumbnails;

    protected string $uploadDirectory = 'uploads/blog/thumbnails';

    public function index(Request $request): View
    {
        $data = [
            'page_title' => 'Blogs',
            'blogs' => Blog::query()
                ->with(['admin:id,name', 'staff:id,name'])
                ->when($request->filled('search'), fn($q) => $q->where('title', 'like', '%' . $request->search . '%'))
                ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
                ->when($request->filled('type'), fn($q) => $q->where('type', $request->type))
                ->latest()
                ->paginate(15)
                ->withQueryString(),
        ];

        return view('frontend.staff.pages.blogs.index', $data);
    }

    public function create(): View
    {
        return view('frontend.staff.pages.blogs.create');
    }

    public function store(StoreBlogRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['staff_id'] = Auth::id();
        $data['admin_id'] = null;
        $data['is_featured'] = $request->boolean('is_featured');
        $data['slug'] = Blog::generateUniqueSlug($request->title);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $this->uploadFile($request->file('thumbnail'), $this->uploadDirectory);
        } elseif ($request->filled('fetched_thumbnail_url')) {
            $data['thumbnail'] = $this->downloadAndStoreBlogThumbnail($request->fetched_thumbnail_url);
        }

        if ($data['status'] === 'draft') {
            $data['published_at'] = null;
        } elseif ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        Blog::create($data);

        return redirect()
            ->route('staff.blogs.index')
            ->with('success', 'Blog created successfully.');
    }

    public function show(Blog $blog): View
    {
        return view('frontend.staff.pages.blogs.show', compact('blog'));
    }

    public function edit(Blog $blog): View
    {
        return view('frontend.staff.pages.blogs.edit', compact('blog'));
    }

    public function update(UpdateBlogRequest $request, Blog $blog): RedirectResponse
    {
        $data = $request->validated();
        $data['is_featured'] = $request->boolean('is_featured');

        if ($blog->title !== $data['title']) {
            $data['slug'] = Blog::generateUniqueSlug($data['title'], $blog->id);
        }

        if ($request->hasFile('thumbnail')) {
            $this->deleteIfImageExist($blog->thumbnail);
            $data['thumbnail'] = $this->uploadFile($request->file('thumbnail'), $this->uploadDirectory);
        } elseif ($request->filled('fetched_thumbnail_url')) {
            $this->deleteIfImageExist($blog->thumbnail);
            $data['thumbnail'] = $this->downloadAndStoreBlogThumbnail($request->fetched_thumbnail_url);
        }

        if ($data['status'] === 'draft') {
            $data['published_at'] = null;
        } elseif ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $blog->update($data);

        return redirect()
            ->route('staff.blogs.index')
            ->with('success', 'Blog updated successfully.');
    }

    public function destroy(Blog $blog): RedirectResponse
    {
        $this->deleteIfImageExist($blog->thumbnail);
        $blog->delete();

        return redirect()
            ->route('staff.blogs.index')
            ->with('success', 'Blog moved to trash.');
    }

    public function fetchThumbnail(Request $request)
    {
        $request->validate([
            'embed_url' => 'required|string',
            'type' => 'required|in:facebook,tiktok,youtube',
        ]);

        $cleanUrl = $this->extractUrlFromInput($request->embed_url);
        if (!$cleanUrl) {
            return response()->json(['success' => false, 'message' => 'Could not find a valid URL in the pasted content.'], 422);
        }

        $thumbnailUrl = match ($request->type) {
            'youtube' => $this->getYoutubeThumbnail($cleanUrl),
            'tiktok' => $this->getTiktokThumbnail($cleanUrl),
            'facebook' => $this->getFacebookThumbnail($cleanUrl),
        };

        if (!$thumbnailUrl) {
            return response()->json(['success' => false, 'message' => 'Could not extract thumbnail from this URL.'], 422);
        }

        return response()->json(['success' => true, 'thumbnail_url' => $thumbnailUrl]);
    }
}
