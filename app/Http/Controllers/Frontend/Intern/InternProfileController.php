<?php
namespace App\Http\Controllers\Frontend\Intern;
use App\Http\Controllers\Controller;
use App\Traites\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
class InternProfileController extends Controller
{
    use FileUpload;
    public function edit()
    {
        return view('frontend.Intern.pages.profile-edit', [
            'user' => Auth::user(),
            'page_title' => 'Profile Settings',
        ]);
    }
    public function update(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'khmer_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'alternate_phone' => ['nullable', 'string', 'max:20'],
            'location' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'in:male,female,other'],
            'dob' => ['nullable', 'date_format:d M Y'],
            'nationality' => ['nullable', 'string', 'max:255'],
            'headline' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'document' => ['nullable', 'file', 'max:5120'],
        ]);
        if ($request->hasFile('image')) {
            $this->deleteIfImageExist($user->image);
            $validated['image'] = $this->uploadFile($request->file('image'), 'uploads/interns/profile');
        }
        if ($request->hasFile('document')) {
            $this->deleteIfImageExist($user->document);
            $validated['document'] = $this->uploadFile($request->file('document'), 'uploads/interns/resume');
        }
        if (!empty($validated['dob'])) {
            $validated['dob'] = Carbon::createFromFormat('d M Y', $validated['dob'])->format('Y-m-d');
        }
        $user->update($validated);
        return back()->with('success', 'Profile updated successfully.')->with('active_tab', 'profile');
    }
    public function updateSocial(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'website' => ['nullable', 'url', 'max:255'],
            'facebook' => ['nullable', 'url', 'max:255'],
            'x' => ['nullable', 'url', 'max:255'],
            'linkedin' => ['nullable', 'url', 'max:255'],
            'github' => ['nullable', 'url', 'max:255'],
            'instagram' => ['nullable', 'url', 'max:255'],
            'telegram' => ['nullable', 'string', 'max:255'],
            'tiktok' => ['nullable', 'url', 'max:255'],
            'youtube' => ['nullable', 'url', 'max:255'],
        ]);
        if (!empty($validated['telegram'])) {
            $telegram = trim($validated['telegram']);
            if (!str_starts_with($telegram, 'http')) {
                $telegram = 'https://t.me/' . ltrim($telegram, '@');
            }
            $validated['telegram'] = $telegram;
        }
        $user->update($validated);
        return back()
            ->with('success', 'Social links updated successfully.')
            ->with('active_tab', 'social');
    }
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Current password is incorrect.'])
                ->withInput();
        }
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);
        return back()->with('success', 'Password updated successfully.')->with('active_tab', 'password');
    }
}
