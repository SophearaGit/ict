<div class="row">
    <!-- Page Header -->
    <div class="col-lg-12 col-md-12 col-12">
        <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
            <div class="mb-3 mb-md-0">
                <h1 class="mb-1 h2 fw-bold">
                    @if (Route::is('admin.course-language.index'))
                        Courses Lanuage
                    @else
                    @endif
                </h1>
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        @if (Route::is('admin.course-language.*'))
                            <li class="breadcrumb-item active">
                                <a href="{{ route('admin.course-language.index') }}">Course Management</a>
                            </li>
                        @endif
                        <li class="breadcrumb-item active" aria-current="page">
                            @if (Route::is('admin.course-language.index'))
                                Course Language
                            @else
                            @endif
                        </li>
                    </ol>
                </nav>
            </div>
            <div>
                @if (Route::is('admin.course-language.index'))
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#newCatgory">
                        Add New Language
                    </a>
                @else
                @endif
            </div>
        </div>
    </div>
</div>
