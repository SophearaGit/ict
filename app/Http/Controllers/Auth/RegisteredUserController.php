<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traites\FileUpload;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    use FileUpload;
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $data = [
            'page_title' => 'ICT | Register',
        ];
        return view('auth.register', $data);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Self-entered student registration (name/email/password typed in
        // by hand) is intentionally disabled — it let anyone create an
        // account with made-up info and an email nobody verified they
        // actually own. Google sign-in (see GoogleAuthController) is now
        // the only way a student account gets created; it's checked first
        // and rejected before touching the rest of this method, in case
        // this endpoint is ever hit directly (e.g. a scripted request)
        // rather than through the (now Google-only) register page.
        if ($request->type === 'student') {
            return redirect()->route('register')
                ->with('status', 'Student registration is now done with Google — please use the "Register with Google" button.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if ($request->type === 'instructor') {

            $request->validate([
                'document' => ['required', 'mimes:pdf,doc,docx,jpg,png', 'max:12000'],
            ]);

            $filePath = $this->uploadFile($request->file('document'));

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'student',
                'approval_status' => 'pending',
                'document' => $filePath,
            ]);
        } else {
            abort(400, 'Invalid registration type.');
        }

        event(new Registered($user));

        Auth::login($user);

        if ($request->user()->role == 'student') {
            return redirect()->intended(route('student.dashboard', false))
                ->with('success', 'Registration successful! Welcome to the student dashboard, ' . $request->user()->name . '.');
        } elseif ($request->user()->role == 'instructor') {
            return redirect()->intended(route('instructor.dashboard', false))
                ->with('success', 'Registration successful! Your instructor application is pending review. We will notify you once it has been approved, ' . $request->user()->name . '.');
        }

        return redirect()->intended(route('dashboard', false));
    }
}
