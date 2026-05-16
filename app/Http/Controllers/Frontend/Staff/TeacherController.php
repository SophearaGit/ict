<?php

namespace App\Http\Controllers\Frontend\Staff;

use App\Http\Controllers\Controller;
use App\Models\ICTCourse;
use App\Models\User;
use App\Traites\FileUpload;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    use FileUpload;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $instructors = User::with(['courses'])
            ->where('role', 'instructor')
            ->where('approval_status', 'approved')
            ->whereNotNull('document')
            // 🔍 Search
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%");
            })
            ->latest()
            ->paginate(8)
            ->withQueryString(); // 🔥 VERY IMPORTANT

        $subjects = ICTCourse::pluck('title')->unique();

        return view('frontend.staff.pages.teacher.index', [
            'page_title' => 'ICT | ADMIN | INSTRUCTORS',
            'instructors' => $instructors,
            'subjects' => $subjects,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'khmer_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'dob' => 'nullable|date',
            'gender' => 'required|in:male,female,other',
            'password' => 'required|min:8|confirmed',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $imagePath = 'no-img.jpg';
        if ($request->hasFile('image')) {
            $imagePath = $this->uploadFile($request->file('image'), 'uploads/teachers/images');
        }

        $documentPath = null;
        if ($request->hasFile('document')) {
            $documentPath = $this->uploadFile($request->file('document'), 'uploads/teachers/documents');
        }

        User::create([
            'name' => $request->name,
            'khmer_name' => $request->khmer_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'password' => bcrypt($request->password),
            'location' => $request->location ?? 'Phnom Penh, Cambodia',
            'image' => $imagePath,
            'document' => $documentPath ?? 'document.jpg',
            'role' => 'instructor',
            'status' => 'active',
            'approval_status' => 'approved',
            'registered_by_staff_id' => auth()->id(),
            'email_verified_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Teacher added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $instructor = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'khmer_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|string|max:20',
            'dob' => 'nullable|date',
            'gender' => 'required|in:male,female',
            'password' => 'nullable|min:8|confirmed',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $this->deleteIfImageExist($instructor->image);
            $instructor->image = $this->uploadFile($request->file('image'), 'uploads/teachers/images');
        }

        // Handle document upload
        if ($request->hasFile('document')) {
            $this->deleteIfImageExist($instructor->document);
            $instructor->document = $this->uploadFile($request->file('document'), 'uploads/teachers/documents');
        }

        // Only update password if provided
        if ($request->filled('password')) {
            $instructor->password = bcrypt($request->password);
        }

        $instructor->name = $request->name;
        $instructor->khmer_name = $request->khmer_name;
        $instructor->email = $request->email;
        $instructor->phone = $request->phone;
        $instructor->dob = $request->dob;
        $instructor->gender = $request->gender;
        $instructor->location = $request->location;
        $instructor->save();

        return redirect()->back()->with('success', 'Teacher updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $instructor = User::findOrFail($id);

        // Delete image if not default
        if ($instructor->image && $instructor->image !== 'no-img.jpg') {
            $this->deleteIfImageExist($instructor->image);
        }

        // Delete document if exists
        if ($instructor->document && $instructor->document !== 'document.jpg') {
            $this->deleteIfImageExist($instructor->document);
        }

        $instructor->delete();

        return redirect()->back()->with('success', 'Teacher deleted successfully.');
    }
}
