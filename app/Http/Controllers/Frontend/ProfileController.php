<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\ProfileUpdatePasswordRequest;
use App\Http\Requests\Frontend\ProfileUpdateRequest;
use App\Http\Requests\Frontend\ProfileUpdateSocialLink;
use App\Traites\FileUpload;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ProfileController extends Controller
{

    use FileUpload;

    /*******************************************************
     * PROFILE VIEW
     *******************************************************/
    public function getEditProfile(): View
    {
        $data = [
            'page_title' => 'ICT Center | Edit Profile',
        ];

        return view('frontend.student.profile-edit', $data);
    }
    public function teacherProfileEdit(): View
    {
        $data = [
            'page_title' => 'ICT Center | Edit Profile',
        ];

        return view('frontend.instructor.pages.profile-edit', $data);
    }
    public function StaffProfileEdit(): View
    {
        $data = [
            'page_title' => 'ICT | STAFF | ACCOUNT SETTINGS',
            'user' => Auth::user(),
        ];

        return view('frontend.staff.pages.profile.profile', $data);
    }

    /*******************************************************
     * PROFILE SUBMIT
     *******************************************************/
    public function profileEditSubmit(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->back();
        }

        if ($request->hasFile('avatar')) {
            $avatar = $this->uploadFile($request->file('avatar'));
            $this->deleteIfImageExist($user->image);
            $user->image = $avatar;
        }

        $user->name = $request->name;
        $user->headline = $request->headline;
        $user->email = $request->email;
        $user->gender = $request->gender;
        $user->bio = $request->bio;
        $user->save();

        return redirect()->back()
            ->with('success', 'Profile updated successfully');
    }
    public function teacherProfileUpdate(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->back();
        }

        if ($request->hasFile('avatar')) {
            $avatar = $this->uploadFile($request->file('avatar'));
            $this->deleteIfImageExist($user->image);
            $user->image = $avatar;
        }

        $user->name = $request->name;
        $user->headline = $request->headline;
        $user->email = $request->email;
        $user->gender = $request->gender;
        $user->bio = $request->bio;
        $user->save();

        return redirect()->back()
            ->with('success', 'Profile updated successfully');
    }
    public function StaffProfileUpdate(Request $request): RedirectResponse
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'khmer_name' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female,other',
            'bio' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::user();

        if (!$user) {
            return redirect()->back();
        }

        if ($request->hasFile('image')) {
            $avatar = $this->uploadFile($request->file('image'));
            $this->deleteIfImageExist($user->image);
            $user->image = $avatar;
        }

        if ($request->filled('password') && $request->filled('current_password')) {

            $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' => ['required', Password::defaults(), 'confirmed'],
            ]);

            $user->password = Hash::make($request->password);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->khmer_name = $request->khmer_name;
        $user->gender = $request->gender;
        $user->bio = $request->bio;
        $user->save();

        return redirect()->back()
            ->with('success', 'Profile updated successfully');
    }

    /*******************************************************
     * SECURITY VIEW
     *******************************************************/
    public function security(): View
    {
        $data = [
            'page_title' => 'ICT Center | Security',
        ];
        return view('frontend.student.security', $data);
    }
    public function teacherSecurity(): View
    {
        $data = [
            'page_title' => 'ICT Center | Security',
        ];
        return view('frontend.instructor.pages.security', $data);
    }

    /*******************************************************
     * SECURITY SUBMIT
     *******************************************************/
    public function securityUpdate(ProfileUpdatePasswordRequest $request): RedirectResponse
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->back();
        }

        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->back()
            ->with('success', 'Password updated successfully');
    }
    public function teacherSecurityUpdate(ProfileUpdatePasswordRequest $request): RedirectResponse
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->back();
        }

        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->back()
            ->with('success', 'Password updated successfully');
    }


    /*******************************************************
     * SOCIAL PROFILE VIEW
     *******************************************************/
    public function socialProfile(): View
    {
        $data = [
            'page_title' => 'ICT Center | Social Profile',
        ];
        return view('frontend.student.social-profile', $data);
    }
    public function teacherSocialProfile(): View
    {
        $data = [
            'page_title' => 'ICT Center | Social Profile',
        ];
        return view('frontend.instructor.pages.social-profile', $data);
    }

    /*******************************************************
     * SOCIAL PROFILE SUBMIT
     *******************************************************/
    public function socialProfileUpdate(ProfileUpdateSocialLink $request): RedirectResponse
    {
        $user = Auth::user();
        $user->facebook = $request->facebook;
        $user->x = $request->x;
        $user->linkedin = $request->linkedin;
        $user->website = $request->website;
        $user->github = $request->github;
        $user->instagram = $request->instagram;
        $user->youtube = $request->youtube;
        $user->save();

        return redirect()->back()
            ->with('success', 'Social link updated successfully');
    }
    public function teacherSocialProfileUpdate(ProfileUpdateSocialLink $request): RedirectResponse
    {
        $user = Auth::user();
        $user->facebook = $request->facebook;
        $user->x = $request->x;
        $user->linkedin = $request->linkedin;
        $user->website = $request->website;
        $user->github = $request->github;
        $user->instagram = $request->instagram;
        $user->youtube = $request->youtube;
        $user->save();

        return redirect()->back()
            ->with('success', 'Social link updated successfully');
    }
    public function StaffSocialProfileUpdate(ProfileUpdateSocialLink $request): RedirectResponse
    {
        $user = Auth::user();
        $user->facebook = $request->facebook;
        $user->x = $request->x;
        $user->linkedin = $request->linkedin;
        $user->website = $request->website;
        $user->github = $request->github;
        $user->instagram = $request->instagram;
        $user->youtube = $request->youtube;
        $user->save();

        return redirect()->back()
            ->with('success', 'Social link updated successfully');
    }
}
