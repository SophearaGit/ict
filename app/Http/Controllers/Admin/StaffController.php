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

class StaffController extends Controller
{

    use FileUpload;

    public function index(Request $request): View
    {
        $data = [
            'page_title' => 'ICT | ADMIN | STAFFS',
            'staffs' => User::whereIn('role', ['staff', 'unknown'])
                ->where('approval_status', 'approved')
                ->whereNotNull('document')
                ->when($request->filled('search'), function ($query) use ($request): void {
                    $query->where('name', 'like', "%{$request->search}%");
                })
                ->latest()->paginate(10),
        ];
        return view('admin.pages.user.staff.staff', $data);
    }

    public function create(): View
    {
        return view('admin.pages.user.staff.add');
    }

    public function edit($id): View
    {
        $staff = User::findOrFail($id);
        $data = [
            'staff' => $staff,
        ];
        return view('admin.pages.user.staff.edit', $data);
    }


    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'document' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:12000',
        ]);

        if ($request->hasFile('document')) {
            $filePath = $this->uploadFile($request->file('document'));
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'staff',
            'approval_status' => 'approved',
            'document' => $filePath,
        ]);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member added successfully.');
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $staff = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $staff->id,
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

        $staff->update($data);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member updated successfully.');
    }

    public function toggle($id): RedirectResponse
    {
        $staff = User::findOrFail($id);

        if ($staff->role === 'unknown') {
            $staff->role = 'staff';
        } else {
            $staff->role = 'unknown';
        }

        $staff->save();

        return back()->with('success', 'Staff member status updated successfully.');
    }


    public function destroy($id): JsonResponse
    {
        $staff = User::findOrFail($id);

        // delete if document and image exist
        if ($staff->document) {
            $this->deleteIfImageExist($staff->document);
        }

        if ($staff->image) {
            $this->deleteIfImageExist($staff->image);
        }

        $staff->delete();

        return response()->json([
            'message' => 'Staff member removed successfully.'
        ]);
    }

}
