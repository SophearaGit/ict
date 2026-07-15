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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
class BlogController extends Controller
{
    use FileUpload;
    protected string $uploadDirectory = 'uploads/blog/thumbnails';
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
        } elseif ($request->filled('fetched_thumbnail_url')) {
            $data['thumbnail'] = $this->downloadAndStoreThumbnail($request->fetched_thumbnail_url);
        }
        // ADD THIS BLOCK — right here, where published_at is being decided
        if ($data['status'] === 'draft') {
            $data['published_at'] = null;
        } elseif ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }
        // 'scheduled' needs no extra handling — published_at already came from the form
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
            $data['thumbnail'] = $this->uploadFile($request->file('thumbnail'), $this->uploadDirectory);
        } elseif ($request->filled('fetched_thumbnail_url')) {
            $data['thumbnail'] = $this->downloadAndStoreThumbnail($request->fetched_thumbnail_url);
        }
        // ADD THIS BLOCK — replaces your old published_at if-check
        if ($data['status'] === 'draft') {
            $data['published_at'] = null;
        } elseif ($data['status'] === 'published') {
            $data['published_at'] = now(); // always "now" — no more backdating option
        }
        // scheduled: published_at already validated & submitted from the form
        // 'scheduled' needs no extra handling — published_at already came from the form
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
    private function extractUrlFromInput(string $input): ?string
    {
        if (filter_var(trim($input), FILTER_VALIDATE_URL)) {
            return trim($input);
        }

        if (preg_match('/[?&]href=([^&"]+)/', $input, $matches)) {
            return urldecode($matches[1]);
        }

        if (preg_match('/cite="([^"]+)"/', $input, $matches)) {
            return $matches[1];
        }

        if (preg_match('/data-href="([^"]+)"/', $input, $matches)) {
            return $matches[1];
        }

        if (preg_match('/src="(https?:\/\/(?:www\.)?youtube(?:-nocookie)?\.com\/embed\/[^"]+)"/', $input, $matches)) {
            return $matches[1];
        }

        if (preg_match('/https?:\/\/[^\s"\'<>]+/', $input, $matches)) {
            return $matches[0];
        }

        return null;
    }
    private function getYoutubeThumbnail(string $url): ?string
    {
        preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $matches);
        if (empty($matches[1])) {
            return null;
        }
        $videoId = $matches[1];
        $maxres = "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg";
        $headers = @get_headers($maxres);
        if ($headers && strpos($headers[0], '200')) {
            return $maxres;
        }
        return "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg";
    }
    private function getTiktokThumbnail(string $url): ?string
    {
        $response = Http::get('https://www.tiktok.com/oembed', [
            'url' => $url,
        ]);
        return $response->successful() ? $response->json('thumbnail_url') : null;
    }
    private function getFacebookThumbnail(string $url): ?string
    {
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (compatible; facebookexternalhit/1.1; +http://www.facebook.com/externalhit_uatext.php)',
            ])->timeout(10)->get($url);
            if (!$response->successful()) {
                return null;
            }
            if (preg_match('/<meta property="og:image" content="([^"]+)"/', $response->body(), $matches)) {
                return html_entity_decode($matches[1]);
            }
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
    private function downloadAndStoreThumbnail(string $url): ?string
    {
        try {
            $response = Http::timeout(10)->get($url);
            if (!$response->successful()) {
                return null;
            }
            $directory = $this->uploadDirectory ?? 'uploads/blog/thumbnails';
            $destination = public_path($directory);
            if (!File::isDirectory($destination)) {
                File::makeDirectory($destination, 0755, true, true);
            }
            $extension = str_contains($response->header('Content-Type'), 'png') ? 'png' : 'jpg';
            $filename = 'ict_' . uniqid() . '.' . $extension;
            File::put($destination . '/' . $filename, $response->body());
            return '/' . $directory . '/' . $filename;
        } catch (\Exception $e) {
            return null;
        }
    }
}
