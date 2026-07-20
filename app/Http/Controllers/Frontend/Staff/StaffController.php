<?php
namespace App\Http\Controllers\Frontend\Staff;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traites\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class StaffController extends Controller
{
    use FileUpload;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $staff = User::query()
            ->where('role', 'staff')
            ->where('approval_status', 'approved')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%");
            })
            ->latest()
            ->paginate(8)
            ->withQueryString();
        return view('frontend.staff.pages.staff.index', [
            'page_title' => 'ICT | STAFF | STAFF',
            'staff' => $staff,
        ]);
    }
    /**
     * Store a newly created staff member.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->admin_approval_edit_staff) {
            return back()->with('error', 'You do not have permission to add staff.');
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'khmer_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'dob' => 'nullable|date',
            'gender' => 'required|in:male,female',
            'password' => 'required|min:8|confirmed',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        $image = 'no-img.jpg';
        if ($request->hasFile('image')) {
            $image = $this->uploadFile(
                $request->file('image'),
                'uploads/staff/images'
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
            'location' => $request->location ?: 'Phnom Penh, Cambodia',
            'image' => $image,
            'role' => 'staff',
            'status' => 'active',
            'approval_status' => 'approved',
            'registered_by_staff_id' => Auth::id(),
            'email_verified_at' => now(),
        ]);
        return back()->with('success', 'Staff member added successfully.');
    }
    /**
     * Update the specified staff member.
     */
    public function update(Request $request, string $id)
    {
        if (!Auth::user()->admin_approval_edit_staff) {
            return back()->with('error', 'You do not have permission to edit staff.');
        }
        $staffMember = User::where('role', 'staff')->findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'khmer_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $staffMember->id,
            'phone' => 'required|string|max:20',
            'dob' => 'nullable|date',
            'gender' => 'required|in:male,female',
            'password' => 'nullable|min:8|confirmed',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        if ($request->hasFile('image')) {
            $this->deleteIfImageExist($staffMember->image);
            $staffMember->image = $this->uploadFile(
                $request->file('image'),
                'uploads/staff/images'
            );
        }
        if ($request->filled('password')) {
            $staffMember->password = bcrypt($request->password);
        }
        $staffMember->name = $request->name;
        $staffMember->khmer_name = $request->khmer_name;
        $staffMember->email = $request->email;
        $staffMember->phone = $request->phone;
        $staffMember->dob = $request->dob;
        $staffMember->gender = $request->gender;
        $staffMember->location = $request->location;
        $staffMember->save();
        return back()->with('success', 'Staff member updated successfully.');
    }
    /**
     * Remove the specified staff member.
     */
    public function destroy(string $id)
    {
        if (!Auth::user()->admin_approval_edit_staff) {
            return back()->with('error', 'You do not have permission to delete staff.');
        }
        $staffMember = User::where('role', 'staff')->findOrFail($id);
        if ($staffMember->image && $staffMember->image !== 'no-img.jpg') {
            $this->deleteIfImageExist($staffMember->image);
        }
        $staffMember->delete();
        return back()->with('success', 'Staff member deleted successfully.');
    }
}
