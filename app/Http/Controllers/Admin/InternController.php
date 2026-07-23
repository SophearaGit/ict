<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traites\FileUpload;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\RedirectResponse;
class InternController extends Controller
{
    use FileUpload;
    public function index(Request $request): View
    {
        $data = [
            'page_title' => 'ICT | ADMIN | INTERNS',
            'interns' => User::whereIn('role', ['intern', 'unknown'])
                ->where('approval_status', 'approved')
                ->whereNotNull('document')
                ->withCount('reports')
                ->when($request->filled('search'), function ($query) use ($request): void {
                    $query->where(function ($q) use ($request): void {
                        $q->where('name', 'like', "%{$request->search}%")
                            ->orWhere('email', 'like', "%{$request->search}%");
                    });
                })
                ->latest()->paginate(12)
                ->withQueryString(),
        ];
        return view('admin.pages.user.intern.intern', $data);
    }
    public function create(): View
    {
        return view('admin.pages.user.intern.add');
    }
    public function edit($id): View
    {
        $intern = User::findOrFail($id);
        $data = [
            'intern' => $intern,
        ];
        return view('admin.pages.user.intern.edit', $data);
    }
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'document' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:12000',
        ]);
        $filePath = null;
        if ($request->hasFile('document')) {
            $filePath = $this->uploadFile($request->file('document'));
        }
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'intern',
            'approval_status' => 'approved',
            'document' => $filePath,
            // Explicit default so the view's fallback check (`== 'no-img.jpg'`)
            // actually matches instead of comparing against null.
            'image' => 'no-img.jpg',
        ]);
        return redirect()->route('admin.intern.index')
            ->with('success', 'Intern added successfully.');
    }
    public function update(Request $request, $id): RedirectResponse
    {
        $intern = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $intern->id,
            'password' => 'nullable|string|min:8|confirmed',
            'document' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:12000',
        ]);
        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];
        // Update password only if filled
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        // Update document if uploaded
        if ($request->hasFile('document')) {
            $data['document'] = $this->uploadFile($request->file('document'));
        }
        $intern->update($data);
        return redirect()->route('admin.intern.index')
            ->with('success', 'Intern updated successfully.');
    }
    public function toggle(User $user): RedirectResponse
    {
        // NOTE: this still repurposes `role` (intern <-> unknown) to represent
        // enabled/disabled. Left as-is to avoid touching behavior elsewhere in
        // the app that may depend on this convention, but `status` or
        // `approval_status` would be a more correct long-term home for this.
        if ($user->role === 'unknown') {
            $user->role = 'intern';
        } else {
            $user->role = 'unknown';
        }
        $user->save();
        return back()->with('success', 'Intern status updated successfully.');
    }
    public function destroy($id): JsonResponse
    {
        $intern = User::findOrFail($id);
        // delete if document and image exist
        if ($intern->document) {
            $this->deleteIfImageExist($intern->document);
        }
        if ($intern->image) {
            $this->deleteIfImageExist($intern->image);
        }
        $intern->delete();
        return response()->json([
            'message' => 'Intern removed successfully.'
        ]);
    }
}
