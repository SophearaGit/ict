<div class="card-header">
    <h3 class="mb-0">
        @if (Route::is('instructor.profile.edit'))
            Profile Details
        @elseif(Route::is('instructor.security'))
            Security Settings
        @elseif(Route::is('instructor.social.profile'))
            Social Profiles
        @elseif(Route::is('instructor.courses.index'))
            My Online Courses
        @elseif(Route::is('instructor.courses.real_time'))
            My Real Time Courses
        @else
        @endif
    </h3>
    <p class="mb-0">
        @if (Route::is('instructor.profile.edit'))
            You have full control to manage your own account setting.
        @elseif(Route::is('instructor.security'))
            Change your password and secure your account here.
        @elseif(Route::is('instructor.social.profile'))
            Connect your social media accounts and let your students know more about you.
        @elseif(Route::is('instructor.courses.index'))
            Manage your online courses here. You can create, edit, and delete your courses as needed.
        @elseif(Route::is('instructor.courses.real_time'))
            Manage your real time courses here. You can create, edit, and delete your courses as
        @else
        @endif
    </p>
</div>
