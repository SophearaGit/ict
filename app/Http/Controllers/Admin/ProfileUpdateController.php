<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Traites\FileUpload;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ProfileUpdateController extends Controller
{
    use FileUpload;a

    public function profile(): View
    {
        $data = [
            'pageTitle' => 'CAITD | Profile',
            'personalDetails' => Auth::user(),
        ];
        return view('admin.pages.profile.index', $data);
    }
    public function update(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'bio' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:51200',
        ]);


        if ($request->hasFile('image')) {
            $imagePath = $this->uploadFile($request->file('image'));
            if ($request->old_image) {
                $this->deleteIfImageExist($request->old_image);
            }
            $validatedData['image'] = $imagePath;
        }

        Auth::guard('admin')->user()->update(
            [
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'bio' => $validatedData['bio'],
                'image' => $validatedData['image'] ?? Auth::guard('admin')->user()->image,
            ]
        );

        return redirect()->back()
            ->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'current_password' => 'required|current_password',
                'password' => 'required|string|min:8|confirmed',
            ]
        );

        $user = Auth::guard('admin')->user();
        $user->password = bcrypt($validated['password']);
        $user->save();

        return redirect()->back()
            ->with('success', 'Password updated successfully.');
    }

}
