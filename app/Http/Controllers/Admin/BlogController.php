<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBlogRequest;
use App\Http\Requests\Admin\UpdateBlogRequest;
use App\Models\Blog;
use App\Traites\FileUpload;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
class BlogController extends Controller
{
    use FileUpload;
    protected string $uploadDirectory = 'blog/thumbnails';
    public function index(Request $request): View
    {
        $data = [
            'page_title' => 'Blogs',
            'blogs' => Blog::query()
                ->with('admin:id,name')
                ->when(
                    $request->filled('search'),
                    fn($q) =>
                    $q->where('title', 'like', '%' . $request->search . '%')
                )
                ->when(
                    $request->filled('status'),
                    fn($q) =>
                    $q->where('status', $request->status)
                )
                ->when(
                    $request->filled('type'),
                    fn($q) =>
                    $q->where('type', $request->type)
                )
                ->latest()
                ->paginate(15)
                ->withQueryString(),
        ];
        return view('admin.pages.blogs.index', $data);
    }
    public function create(): View
    {
        return view('admin.pages.blogs.create');
    }
    public function store(StoreBlogRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['admin_id'] = auth('admin')->id();
        $data['is_featured'] = $request->boolean('is_featured');
        $data['slug'] = Blog::generateUniqueSlug($request->title);
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $this->uploadFile($request->file('thumbnail'), $this->uploadDirectory);
        }
        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }
        Blog::create($data);
        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Blog created successfully.');
    }
    public function show(Blog $blog): View
    {
        return view('admin.pages.blogs.show', compact('blog'));
    }
    public function edit(Blog $blog): View
    {
        return view('admin.pages.blogs.edit', compact('blog'));
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
        }
        if ($data['status'] === 'published' && empty($blog->published_at) && empty($data['published_at'])) {
            $data['published_at'] = now();
        }
        $blog->update($data);
        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Blog updated successfully.');
    }
    public function destroy(Blog $blog): RedirectResponse
    {
        $this->deleteIfImageExist($blog->thumbnail);
        $blog->delete();
        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Blog moved to trash.');
    }
}
