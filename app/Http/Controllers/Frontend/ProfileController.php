<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\ProfileUpdatePasswordRequest;
use App\Http\Requests\Frontend\ProfileUpdateRequest;
use App\Http\Requests\Frontend\ProfileUpdateSocialLink;
use App\Traites\FileUpload;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ProfileController extends Controller
{

    use FileUpload;

    // Student Profile Edit
    public function getEditProfile(): View
    {
        $data = [
            'page_title' => 'ICT Center | Edit Profile',
        ];

        return view('frontend.student.profile-edit', $data);
    }

    // Teacher Profile Edit
    public function teacherProfileEdit(): View
    {
        $data = [
            'page_title' => 'ICT Center | Edit Profile',
        ];

        return view('frontend.instructor.pages.profile-edit', $data);
    }

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

        // notyf()->success('Profile updated successfully');
        return redirect()->back();
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

        // notyf()->success('Profile updated successfully');
        return redirect()->back();
    }

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

    public function securityUpdate(ProfileUpdatePasswordRequest $request): RedirectResponse
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->back();
        }

        $user->password = bcrypt($request->password);
        $user->save();

        // notyf()->success('Password updated successfully');
        return redirect()->back();
    }

    public function teacherSecurityUpdate(ProfileUpdatePasswordRequest $request): RedirectResponse
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->back();
        }

        $user->password = bcrypt($request->password);
        $user->save();

        // notyf()->success('Password updated successfully');
        return redirect()->back();
    }

    public function socialProfile(): View
    {
        $data = [
            'page_title' => 'ICT Center | Social Profile',
        ];
        return view('frontend.student.social-profile', $data);
    }

    // teacherSocialProfile
    public function teacherSocialProfile(): View
    {
        $data = [
            'page_title' => 'ICT Center | Social Profile',
        ];
        return view('frontend.instructor.pages.social-profile', $data);
    }

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
        // notyf()->success('Social link updated successfully');
        return redirect()->back();
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
        // notyf()->success('Social link updated successfully');
        return redirect()->back();
    }





}
