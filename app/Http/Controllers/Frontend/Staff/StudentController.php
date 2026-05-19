<?php
namespace App\Http\Controllers\Frontend\Staff;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traites\FileUpload;
use Illuminate\Http\Request;
class StudentController extends Controller
{
    use FileUpload;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $students = User::where('role', 'student')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%")->orWhere('email', 'like', "%{$request->search}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();
        return view('frontend.staff.pages.student.index', [
            'page_title' => 'ICT | ADMIN | STUDENTS',
            'students' => $students,
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
        ]);
        $imagePath = 'no-img.jpg';
        if ($request->hasFile('image')) {
            $imagePath = $this->uploadFile($request->file('image'), 'uploads/students/images');
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
            'role' => 'student',
            'status' => 'active',
            'approval_status' => 'approved',
            'registered_by_staff_id' => auth()->id(),
            'email_verified_at' => now(),
        ]);
        return redirect()->back()->with('success', 'Student added successfully.');
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
        $student = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'khmer_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|string|max:20',
            'dob' => 'nullable|date',
            'gender' => 'required|in:male,female,other',
            'password' => 'nullable|min:8|confirmed',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        if ($request->hasFile('image')) {
            $this->deleteIfImageExist($student->image);
            $student->image = $this->uploadFile($request->file('image'), 'uploads/students/images');
        }
        if ($request->filled('password')) {
            $student->password = bcrypt($request->password);
        }
        $student->name = $request->name;
        $student->khmer_name = $request->khmer_name;
        $student->email = $request->email;
        $student->phone = $request->phone;
        $student->dob = $request->dob;
        $student->gender = $request->gender;
        $student->location = $request->location;
        $student->save();
        return redirect()->back()->with('success', 'Student updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = User::findOrFail($id);
        if ($student->image && $student->image !== 'no-img.jpg') {
            $this->deleteIfImageExist($student->image);
        }
        $student->delete();
        return redirect()->back()->with('success', 'Student deleted successfully.');
    }
}
