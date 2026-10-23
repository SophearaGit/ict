@php
    $phIconMap = [
        'staff.student.registration' => 'ti-user-plus',
        'staff.courses.index' => 'ti-bookmark',
        'staff.courses.create' => 'ti-bookmark',
        'staff.courses.edit' => 'ti-bookmark',
        'staff.courses.show' => 'ti-bookmark',
        'staff.curriculum.index' => 'ti-list-details',
        'staff.courses.curriculum.show' => 'ti-list-details',
        'staff.schedules.index' => 'ti-calendar',
        'staff.schedules.create' => 'ti-calendar',
        'staff.schedules.edit' => 'ti-calendar',
        'staff.invoices' => 'ti-file-invoice',
        'staff.reports.index' => 'ti-flag',
        'staff.profile.edit' => 'ti-user-circle',
        'staff.teacher.index' => 'ti-chalkboard',
        'staff.teacher.create' => 'ti-chalkboard',
        'staff.teacher.edit' => 'ti-chalkboard',
        'staff.student.index' => 'ti-user-plus',
        'staff.intern.index' => 'ti-id-badge-2',
        'staff.intern.create' => 'ti-id-badge-2',
        'staff.intern.edit' => 'ti-id-badge-2',
        'staff.staff.index' => 'ti-user-shield',
        'staff.staff.create' => 'ti-user-shield',
        'staff.staff.edit' => 'ti-user-shield',
        'staff.course-categories.index' => 'ti-category',
        'staff.blogs.index' => 'ti-news',
        'staff.blogs.create' => 'ti-news',
        'staff.blogs.edit' => 'ti-news',
        'staff.blogs.show' => 'ti-news',
        'staff.projects.index' => 'ti-apps',
        'staff.projects.create' => 'ti-apps',
        'staff.projects.edit' => 'ti-apps',
        'staff.projects.show' => 'ti-apps',
        'staff.project-categories.index' => 'ti-category-2',
    ];
    $phIcon = 'ti-layout-grid-add';
    foreach ($phIconMap as $phRouteName => $phIconName) {
        if (Route::is($phRouteName)) {
            $phIcon = $phIconName;
            break;
        }
    }
@endphp

<div class="card ph-card">
    <div class="ph-body">
        <div class="row align-items-center g-3">
            <div class="col-12 col-md-9">
                <div class="ph-title">
                    <span class="ph-title-icon"><i class="ti {{ $phIcon }}"></i></span>
                    <h4 class="mb-0">
                        @if (Route::is('staff.student.registration'))
                            Student Management
                        @elseif (Route::is('staff.courses.index') || Route::is('staff.courses.create') || Route::is('staff.courses.edit'))
                            Courses
                        @elseif (Route::is('staff.curriculum.index') || Route::is('staff.courses.curriculum.show'))
                            Curriculum
                        @elseif (Route::is('staff.schedules.index') || Route::is('staff.schedules.create') || Route::is('staff.schedules.edit'))
                            Schedules
                        @elseif (Route::is('staff.invoices'))
                            Invoices
                        @elseif (Route::is('staff.reports.index'))
                            Reports
                        @elseif (Route::is('staff.profile.edit'))
                            Account Settings
                        @elseif (Route::is('staff.courses.show'))
                            Course Details
                        @elseif (Route::is('staff.teacher.index') || Route::is('staff.teacher.create') || Route::is('staff.teacher.edit'))
                            Teachers
                        @elseif (Route::is('staff.student.index'))
                            Students
                        @elseif (Route::is('staff.intern.index') || Route::is('staff.intern.create') || Route::is('staff.intern.edit'))
                            Interns
                        @elseif (Route::is('staff.staff.index') || Route::is('staff.staff.create') || Route::is('staff.staff.edit'))
                            Staff
                        @elseif (Route::is('staff.course-categories.index'))
                            Categories
                        @elseif (Route::is('staff.blogs.index') || Route::is('staff.blogs.create') || Route::is('staff.blogs.edit') || Route::is('staff.blogs.show'))
                            Blogs
                        @elseif (Route::is('staff.projects.index') || Route::is('staff.projects.create') || Route::is('staff.projects.edit') || Route::is('staff.projects.show'))
                            Projects
                        @elseif (Route::is('staff.project-categories.index'))
                            Categories
                        @endif
                    </h4>
                </div>

                <nav aria-label="breadcrumb">
                    <ol class="ph-breadcrumb">
                        <li>
                            <a href="{{ route('staff.dashboard') }}">
                                <i class="ti ti-home-2"></i> Dashboard
                            </a>
                        </li>

                        @if (Route::is('staff.schedules.index') ||
                                Route::is('staff.schedules.create') ||
                                Route::is('staff.schedules.edit') ||
                                Route::is('staff.course-categories.index') ||
                                Route::is('staff.curriculum.index') ||
                                Route::is('staff.courses.curriculum.show'))
                            <li>
                                <span class="ph-sep"><i class="ti ti-chevron-right"></i></span>
                                <a href="{{ route('staff.courses.index') }}">Courses</a>
                            </li>
                        @endif

                        @if (Route::is('staff.blogs.create') || Route::is('staff.blogs.edit') || Route::is('staff.blogs.show'))
                            <li>
                                <span class="ph-sep"><i class="ti ti-chevron-right"></i></span>
                                <a href="{{ route('staff.blogs.index') }}">Blogs</a>
                            </li>
                        @endif

                        @if (Route::is('staff.projects.create') ||
                                Route::is('staff.projects.edit') ||
                                Route::is('staff.projects.show') ||
                                Route::is('staff.project-categories.index'))
                            <li>
                                <span class="ph-sep"><i class="ti ti-chevron-right"></i></span>
                                <a href="{{ route('staff.projects.index') }}">Projects</a>
                            </li>
                        @endif

                        <li>
                            <span class="ph-sep"><i class="ti ti-chevron-right"></i></span>
                            <span class="ph-current" aria-current="page">
                                @if (Route::is('staff.student.registration'))
                                    Student Management
                                @elseif (Route::is('staff.courses.index') || Route::is('staff.courses.create') || Route::is('staff.courses.edit'))
                                    Courses
                                @elseif (Route::is('staff.curriculum.index') || Route::is('staff.courses.curriculum.show'))
                                    Curriculum
                                @elseif (Route::is('staff.schedules.index') || Route::is('staff.schedules.create') || Route::is('staff.schedules.edit'))
                                    Schedules
                                @elseif (Route::is('staff.invoices'))
                                    Invoices
                                @elseif (Route::is('staff.reports.index'))
                                    Reports
                                @elseif (Route::is('staff.profile.edit'))
                                    Account Settings
                                @elseif (Route::is('staff.courses.show'))
                                    Course Details
                                @elseif (Route::is('staff.teacher.index') || Route::is('staff.teacher.create') || Route::is('staff.teacher.edit'))
                                    Teachers
                                @elseif (Route::is('staff.student.index'))
                                    Students
                                @elseif (Route::is('staff.intern.index') || Route::is('staff.intern.create') || Route::is('staff.intern.edit'))
                                    Interns
                                @elseif (Route::is('staff.staff.index') || Route::is('staff.staff.create') || Route::is('staff.staff.edit'))
                                    Staff
                                @elseif (Route::is('staff.course-categories.index'))
                                    Categories
                                @elseif (Route::is('staff.blogs.index'))
                                    Blogs
                                @elseif (Route::is('staff.blogs.create'))
                                    Add
                                @elseif (Route::is('staff.blogs.edit'))
                                    Edit
                                @elseif (Route::is('staff.blogs.show'))
                                    {{ $blog->title ?? 'View' }}
                                @elseif (Route::is('staff.projects.index'))
                                    Projects
                                @elseif (Route::is('staff.projects.create'))
                                    Add
                                @elseif (Route::is('staff.projects.edit'))
                                    Edit
                                @elseif (Route::is('staff.projects.show'))
                                    {{ $project->title ?? 'View' }}
                                @elseif (Route::is('staff.project-categories.index'))
                                    Categories
                                @endif
                            </span>
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="col-md-3 d-none d-md-flex ph-illustration">
                <span class="ph-disc"></span>
                <img src="{{ asset('/admin/assets/dist/images/breadcrumb/ChatBc.png') }}" alt=""
                    class="img-fluid">
            </div>
        </div>
    </div>
</div>
