<div class="card bg-light-info shadow-none position-relative overflow-hidden">
    <div class="card-body px-4 py-3">
        <div class="row align-items-center">
            <div class="col-9">
                <h4 class="fw-semibold mb-8">
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
                    @endif
                </h4>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a class="text-muted text-decoration-none" href="{{ route('staff.dashboard') }}">
                                Dashboard
                            </a>
                        </li>

                        @if (Route::is('staff.schedules.index') ||
                                Route::is('staff.schedules.create') ||
                                Route::is('staff.schedules.edit') ||
                                Route::is('staff.course-categories.index') ||
                                Route::is('staff.curriculum.index') ||
                                Route::is('staff.courses.curriculum.show'))
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ route('staff.courses.index') }}">
                                    Courses
                                </a>
                            </li>
                        @endif

                        <li class="breadcrumb-item active" aria-current="page">
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
                            @endif
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="col-3">
                <div class="text-center mb-n5">
                    <img src="{{ asset('/admin/assets/dist/images/breadcrumb/ChatBc.png') }}" alt=""
                        class="img-fluid mb-n4">
                </div>
            </div>
        </div>
    </div>
</div>
