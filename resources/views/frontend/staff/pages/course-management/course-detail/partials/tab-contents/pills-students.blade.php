<div class="tab-pane fade" id="pills-students" role="tabpanel" aria-labelledby="pills-students-tab" tabindex="0">
    {{-- <div class="d-sm-flex align-items-center justify-content-between mt-3 mb-4">
                <h3 class="mb-3 mb-sm-0 fw-semibold d-flex align-items-center">
                    Students
                    <span class="badge text-bg-secondary fs-2 rounded-4 py-1 px-2 ms-2">
                        {{ $course->students->count() ?? 0 }}
                    </span>
                </h3>
                <form class="position-relative">
                    <input type="text" class="form-control search-chat py-2 ps-5" id="text-srh"
                        placeholder="Search Friends">
                    <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y text-dark ms-3"></i>
                </form>
            </div> --}}
    <div class="row">
        @forelse ($course->students as $student)
            <div class="col-sm-6 col-lg-4">
                <div class="card hover-img">
                    <div class="card-body p-4 text-center border-bottom">
                        <img src="
                                    {{ asset($student->image == 'no-img.jpg' ? 'default-images/user/both.jpg' : $student->image) }}
                                "
                            alt="" class="rounded-circle mb-3" width="80" height="80">
                        <h5 class="fw-semibold mb-0 text-capitalize">
                            {{ Str::limit($student->name, 20) }}
                        </h5>
                        <span class="text-dark fs-2">
                            {{ $student->email }}
                        </span>
                    </div>
                    <ul class="px-2 py-2 bg-light list-unstyled d-flex align-items-center justify-content-center mb-0">

                        <li class="position-relative">
                            <a class="text-primary d-flex align-items-center justify-content-center p-2 fs-5 rounded-circle fw-semibold"
                                href="javascript:void(0)">
                                <i class="ti ti-brand-facebook"></i>
                            </a>
                        </li>
                        <li class="position-relative">
                            <a class="text-danger d-flex align-items-center justify-content-center p-2 fs-5 rounded-circle fw-semibold "
                                href="javascript:void(0)">
                                <i class="ti ti-brand-instagram"></i>
                            </a>
                        </li>
                        <li class="position-relative">
                            <a class="text-info d-flex align-items-center justify-content-center p-2 fs-5 rounded-circle fw-semibold "
                                href="javascript:void(0)">
                                <i class="ti ti-brand-github"></i>
                            </a>
                        </li>
                        <li class="position-relative">
                            <a class="text-secondary d-flex align-items-center justify-content-center p-2 fs-5 rounded-circle fw-semibold "
                                href="javascript:void(0)">
                                <i class="ti ti-brand-twitter"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="mb-0">No students enrolled in this course.</h5>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>
