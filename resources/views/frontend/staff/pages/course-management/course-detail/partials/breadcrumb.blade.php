{{-- ── Breadcrumb ── --}}
<div class="card ph-card mb-4">
    <div class="ph-body">
        <div class="row align-items-center g-3">
            <div class="col-12 col-md-9">
                <div class="ph-title">
                    <span class="ph-title-icon"><i class="ti ti-bookmark"></i></span>
                    <h4 class="mb-0">Course Details</h4>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="ph-breadcrumb">
                        <li>
                            <a href="{{ route('staff.dashboard') }}"><i class="ti ti-home-2"></i> Dashboard</a>
                        </li>
                        <li>
                            <span class="ph-sep"><i class="ti ti-chevron-right"></i></span>
                            <span class="text-muted px-1">Course Management</span>
                        </li>
                        <li>
                            <span class="ph-sep"><i class="ti ti-chevron-right"></i></span>
                            <a href="{{ route('staff.courses.index') }}">Courses</a>
                        </li>
                        <li>
                            <span class="ph-sep"><i class="ti ti-chevron-right"></i></span>
                            <span class="ph-current">{{ Str::limit($course->title, 30) }}</span>
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
