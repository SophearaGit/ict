<div class="card bg-light-info shadow-none position-relative overflow-hidden">
    <div class="card-body px-4 py-3">
        <div class="row align-items-center">
            <div class="col-9">
                <h4 class="fw-semibold mb-8">
                    @if (Route::is('staff.student.registration'))
                        Student Management
                    @elseif (Route::is('staff.courses.index') || Route::is('staff.courses.create'))
                        Course Management
                    @elseif (Route::is('staff.schedules.index') || Route::is('staff.schedules.create'))
                        Schedule Management
                    @elseif (Route::is('staff.invoices'))
                        Invoices
                    @else
                    @endif
                </h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="text-muted text-decoration-none"
                                href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item" aria-current="page">
                            @if (Route::is('staff.student.registration') || Route::is('staff.student.registration'))
                                Student Management
                            @elseif (Route::is('staff.courses.index') || Route::is('staff.courses.create'))
                                Course Management
                            @elseif (Route::is('staff.schedules.index') || Route::is('staff.schedules.create'))
                                Schedule Management
                            @elseif (Route::is('staff.invoices'))
                                Invoices
                            @else
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
