<?php

namespace App\Http\Controllers\Frontend\Staff;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traites\FileUpload;
use Illuminate\Http\Request;

class InternController extends Controller
{
    use FileUpload;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $interns = User::query()
            ->where('role', 'intern')
            ->where('approval_status', 'approved')
            ->whereNotNull('document')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%");
            })
            ->latest()
            ->paginate(8)
            ->withQueryString();

        return view('frontend.staff.pages.intern.index', [
            'page_title' => 'ICT | STAFF | INTERNS',
            'interns' => $interns,
        ]);
    }

    /**
     * Store a newly created intern.
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

        $image = 'no-img.jpg';

        if ($request->hasFile('image')) {
            $image = $this->uploadFile(
                $request->file('image'),
                'uploads/interns/images'
            );
        }

        $document = 'document.jpg';

        if ($request->hasFile('document')) {
            $document = $this->uploadFile(
                $request->file('document'),
                'uploads/interns/documents'
            );
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
            'image' => $image,
            'document' => $document,
            'role' => 'intern',
            'status' => 'active',
            'approval_status' => 'approved',
            'registered_by_staff_id' => auth()->id(),
            'email_verified_at' => now(),
        ]);

        return back()->with('success', 'Intern added successfully.');
    }

    /**
     * Update the specified intern.
     */
    public function update(Request $request, string $id)
    {
        $intern = User::findOrFail($id);

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
            'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $this->deleteIfImageExist($intern->image);
            $intern->image = $this->uploadFile(
                $request->file('image'),
                'uploads/interns/images'
            );
        }

        if ($request->hasFile('document')) {
            $this->deleteIfImageExist($intern->document);
            $intern->document = $this->uploadFile(
                $request->file('document'),
                'uploads/interns/documents'
            );
        }

        if ($request->filled('password')) {
            $intern->password = bcrypt($request->password);
        }

        $intern->name = $request->name;
        $intern->khmer_name = $request->khmer_name;
        $intern->email = $request->email;
        $intern->phone = $request->phone;
        $intern->dob = $request->dob;
        $intern->gender = $request->gender;
        $intern->location = $request->location;

        $intern->save();

        return back()->with('success', 'Intern updated successfully.');
    }

    /**
     * Remove the specified intern.
     */
    public function destroy(string $id)
    {
        $intern = User::findOrFail($id);

        if ($intern->image && $intern->image !== 'no-img.jpg') {
            $this->deleteIfImageExist($intern->image);
        }

        if ($intern->document && $intern->document !== 'document.jpg') {
            $this->deleteIfImageExist($intern->document);
        }

        $intern->delete();

        return back()->with('success', 'Intern deleted successfully.');
    }

    public function create()
    {
    }
    public function show(string $id)
    {
    }
    public function edit(string $id)
    {
    }
}
