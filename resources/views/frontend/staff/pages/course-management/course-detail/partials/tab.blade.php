<ul class="nav nav-pills user-profile-tab justify-content-end mt-2 bg-light-info rounded-2" id="pills-tab" role="tablist">
    <li class="nav-item" role="presentation">
        <button
            class="  nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6"
            id="pills-students-tab" data-bs-toggle="pill" data-bs-target="#pills-students" type="button" role="tab"
            aria-controls="pills-students" aria-selected="false" tabindex="-1">
            <i class="ti ti-user-circle me-2 fs-6"></i>
            <span class="d-none d-md-block">
                Students
            </span>
        </button>
    </li>
    {{-- Teacher Attendance --}}
    <li class="nav-item" role="presentation">
        <button
            class=" active nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6"
            id="pills-attendance-tab" data-bs-toggle="pill" data-bs-target="#pills-attendance" type="button"
            role="tab" aria-controls="pills-attendance" aria-selected="false" tabindex="-1">
            <i class="ti ti-calendar me-2 fs-6"></i>
            <span class="d-none d-md-block">
                Teacher's Attendant
            </span>
        </button>
    </li>
    {{-- Student's Attendant --}}
    <li class="nav-item" role="presentation">
        <button
            class=" nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6"
            id="pills-student-attendance-tab" data-bs-toggle="pill" data-bs-target="#pills-student-attendance"
            type="button" role="tab" aria-controls="pills-student-attendance" aria-selected="false" tabindex="-1">
            <i class="ti ti-calendar me-2 fs-6"></i>
            <span class="d-none d-md-block">
                Student's Attendant
            </span>
        </button>
    </li>
</ul>
