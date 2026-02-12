<div class="card-header">
    <h3 class="mb-0">
        @if (Route::is('instructor.profile.edit'))
            Profile Details
        @elseif(Route::is('instructor.security'))
            Security Settings
        @else
            Account Settings
        @endif
    </h3>
    <p class="mb-0">
        @if (Route::is('instructor.profile.edit'))
            You have full control to manage your own account setting.
        @elseif(Route::is('instructor.security'))
            Change your password and secure your account here.
        @else
            Edit your account settings and change your password here.
        @endif
    </p>
</div>
