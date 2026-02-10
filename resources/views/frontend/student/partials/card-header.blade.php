<!-- Card header -->
<div class="card-header">
    <h3 class="mb-0">
        @if (Route::is('student.profile.edit'))
            Profile Details
        @elseif(Route::is('student.become.instructor'))
            Become an Instructor
        @elseif(Route::is('student.security'))
            Security Settings
        @elseif(Route::is('student.social.profile'))
            Social Profile
        @else
            Account Settings
        @endif
    </h3>
    <p class="mb-0">
        @if (Route::is('student.profile.edit'))
            You have full control to manage your own account setting.
        @elseif(Route::is('student.become.instructor'))
            You can apply to become an instructor here, after admin approval you can start creating courses and earn
            money.
        @elseif(Route::is('student.security'))
            Change your password and secure your account here.
        @elseif(Route::is('student.social.profile'))
            Manage your social media profiles and links here.
        @else
            Edit your account settings and change your password here.
        @endif
    </p>
</div>
