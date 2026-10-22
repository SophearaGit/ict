<?php

namespace App\Http\Controllers\Frontend\Staff;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traites\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            'khmer_name' => ['nullable', 'string', 'max:255', 'regex:/^[\x{1780}-\x{17FF}\s]+$/u'],
            // email alone accepts a dot-less domain like "gmail" as valid, and
            // (same issue as the Teacher form) permits international (RFC 6531)
            // addresses, so a Khmer-script local part like "\u179f\u17bb\u1797\u17b6@gmail.com" still passes
            // it. Add "dns" to confirm the domain is real, and an ASCII-only
            // regex so Khmer (or any other non-Latin script) is rejected outright.
            'email' => ['required', 'email:rfc,dns', 'regex:/^[\x00-\x7F]+$/', 'unique:users,email'],
            'phone' => ['required', 'regex:/^[0-9+\-\s]{8,20}$/'],
            'dob' => 'nullable|date|before:today',
            'gender' => 'required|in:male,female,other',
            'password' => 'required|min:8|confirmed',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ], [
            'khmer_name.regex' => 'Full Name (Khmer) can only contain Khmer script.',
            'phone.regex' => 'Phone must be 8–20 characters and contain only digits, spaces, + or -.',
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
            'khmer_name' => ['nullable', 'string', 'max:255', 'regex:/^[\x{1780}-\x{17FF}\s]+$/u'],
            // Same reasoning as store(): require a real, resolvable domain and
            // plain ASCII so Khmer script in the local part is rejected.
            'email' => ['required', 'email:rfc,dns', 'regex:/^[\x00-\x7F]+$/', 'unique:users,email,' . $id],
            'phone' => ['required', 'regex:/^[0-9+\-\s]{8,20}$/'],
            'dob' => 'nullable|date|before:today',
            'gender' => 'required|in:male,female,other',
            'password' => 'nullable|min:8|confirmed',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ], [
            'khmer_name.regex' => 'Full Name (Khmer) can only contain Khmer script.',
            'phone.regex' => 'Phone must be 8–20 characters and contain only digits, spaces, + or -.',
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
            // Rotate the "Remember Me" token whenever staff reset the
            // intern's password here too, so a remember-me cookie captured
            // under the old password can't silently keep logging the old
            // session back in after this reset (same fix as the intern's
            // own self-service password change in InternProfileController).
            $intern->remember_token = Str::random(60);
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
