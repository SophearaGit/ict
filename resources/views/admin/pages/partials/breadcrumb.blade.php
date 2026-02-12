    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <!-- Page Header -->
            <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
                <div class="mb-3 mb-md-0">
                    <h1 class="mb-1 h2 fw-bold">
                        @if (Route::is('admin.instructor.index'))
                            Instructors
                        @else
                        @endif
                    </h1>
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.instructor.index') }}">
                                    Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                @if (Route::is('admin.instructor.index'))
                                    Instructors
                                @else
                                @endif
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
