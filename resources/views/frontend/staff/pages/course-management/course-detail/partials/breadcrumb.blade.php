{{-- ── Breadcrumb ── --}}
<div class="card bg-light-info shadow-none position-relative overflow-hidden mb-4">
    <div class="card-body px-4 py-3">
        <div class="row align-items-center">
            <div class="col-9">
                <h4 class="fw-semibold mb-8">Course Details</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="text-muted"
                                href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item text-muted">Course Management</li>
                        <li class="breadcrumb-item"><a class="text-muted"
                                href="{{ route('staff.courses.index') }}">Courses</a></li>
                        <li class="breadcrumb-item active">{{ Str::limit($course->title, 30) }}</li>
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
