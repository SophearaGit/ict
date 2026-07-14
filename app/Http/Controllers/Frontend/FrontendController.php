<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\ICTCourse;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index(): View
    {
        $data = [
            'page_title' => 'WELCOME 😁🙏',
            'courses' => ICTCourse::with([
                'instructor',
                'schedule',
                'category'
            ])
                ->where('status', 'active')
                ->latest()
                ->get()
                ->groupBy('title'),
            'instructors' => User::where('role', 'instructor')
                ->withCount('courses')
                ->withCount('enrollments as students_count')
                ->orderByDesc('students_count')
                ->take(4)
                ->get(),
            'featured_instructors' => User::where('role', 'instructor')
                ->withCount('courses')
                ->withCount('enrollments as students_count')
                ->with([
                    'courses' => fn($q) => $q->latest()
                ])
                ->where('approval_status', 'approved')
                ->where('status', 'active')
                ->orderByDesc('students_count')
                ->take(8)
                ->get(),
            'total_students' => User::where('role', 'student')
                ->where('approval_status', 'approved')
                ->count(),
        ];
        return view('frontend.pages.home-new.index', $data);
    }

    public function contact(): View
    {
        $data = [
            'page_title' => 'CONTACT',
        ];
        return view('frontend.pages.home-new.contact', $data);
    }

    public function about(): View
    {
        $data = [
            'page_title' => 'ABOUT US',
        ];
        return view('frontend.pages.home-new.about', $data);
    }


    public function blog(Request $request)
    {
        $featured = Blog::published()
            ->where('is_featured', true)
            ->latest('published_at')
            ->first();

        $blogs = Blog::published()
            ->when(
                $request->filled('search'),
                fn($q) => $q->where(function ($query) use ($request) {
                    $query->where('title', 'like', "%{$request->search}%")
                        ->orWhere('excerpt', 'like', "%{$request->search}%");
                })
            )
            ->when(
                $request->filled('type'),
                fn($q) => $q->where('type', $request->type)
            )
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('frontend.pages.home-new.blog', compact(
            'featured',
            'blogs'
        ));
    }

    public function blogDetails(string $slug)
    {
        $blog = Blog::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $blog->increment('views');

        $related = Blog::published()
            ->where('id', '!=', $blog->id)
            ->where('type', $blog->type)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('frontend.pages.home-new.blog-details', compact('blog', 'related'));
    }
}
