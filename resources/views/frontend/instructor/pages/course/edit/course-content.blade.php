@extends('frontend.instructor.pages.course.course-master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('course-content')
    <!-- Content one -->
    <form class="course_content_form course_form">
        @csrf
        {{-- HIDEN INPUT START --}}
        <input type="hidden" name="course_id" value="{{ request()?->id }}">
        <input type="hidden" name="current_step" value="3">
        <input type="hidden" name="next_step" value="4">
        {{-- HIDEN INPUT ENT --}}
        <div>
            <!-- Card -->
            <div class="card mb-3 border-0">
                <div class="card-header border-bottom px-4 py-3">
                    <h4 class="mb-0">Curriculum</h4>
                </div>
                <!-- Card body -->
                <div class="card-body">
                    <div class="bg-light rounded p-2 mb-4">
                        <h4>Introduction to JavaScript</h4>
                        <!-- List group -->
                        <div class="list-group list-group-flush border-top-0" id="courseList">
                            <div id="courseOne">
                                <div class="list-group-item rounded px-3 text-nowrap mb-1" id="introduction">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h5 class="mb-0 text-truncate">
                                            <a href="#" class="text-inherit">
                                                <i class="fe fe-menu me-1 align-middle"></i>
                                                <span class="align-middle">Introduction</span>
                                            </a>
                                        </h5>
                                        <div>
                                            <a href="#" class="me-1 text-inherit" data-bs-toggle="tooltip"
                                                data-placement="top" aria-label="Edit" data-bs-original-title="Edit">
                                                <i class="fe fe-edit fs-6"></i>
                                            </a>
                                            <a href="#" class="me-1 text-inherit" data-bs-toggle="tooltip"
                                                data-placement="top" aria-label="Delete" data-bs-original-title="Delete">
                                                <i class="fe fe-trash-2 fs-6"></i>
                                            </a>
                                            <a href="#" class="text-inherit" aria-expanded="true"
                                                data-bs-toggle="collapse" data-bs-target="#collapselistOne"
                                                aria-controls="collapselistOne">
                                                <span class="chevron-arrow"><i class="fe fe-chevron-down"></i></span>
                                            </a>
                                        </div>
                                    </div>
                                    <div id="collapselistOne" class="collapse show" aria-labelledby="introduction"
                                        data-bs-parent="#courseList">
                                        <div class="p-md-4 p-2">
                                            <a href="#" class="btn btn-secondary btn-sm">Add Article +</a>
                                            <a href="#" class="btn btn-secondary btn-sm">Add Description +</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item rounded px-3 text-nowrap mb-1" id="development">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h5 class="mb-0 text-truncate">
                                            <a href="#" class="text-inherit">
                                                <i class="fe fe-menu me-1 align-middle"></i>
                                                <span class="align-middle">Installing Development Software</span>
                                            </a>
                                        </h5>
                                        <div>
                                            <a href="#" class="me-1 text-inherit" data-bs-toggle="tooltip"
                                                data-placement="top" aria-label="Edit" data-bs-original-title="Edit">
                                                <i class="fe fe-edit fs-6"></i>
                                            </a>
                                            <a href="#" class="me-1 text-inherit" data-bs-toggle="tooltip"
                                                data-placement="top" aria-label="Delete" data-bs-original-title="Delete">
                                                <i class="fe fe-trash-2 fs-6"></i>
                                            </a>
                                            <a href="#" class="text-inherit" data-bs-toggle="collapse"
                                                data-bs-target="#collapselistTwo" aria-expanded="false"
                                                aria-controls="collapselistTwo">
                                                <span class="chevron-arrow"><i class="fe fe-chevron-down"></i></span>
                                            </a>
                                        </div>
                                    </div>
                                    <div id="collapselistTwo" class="collapse" aria-labelledby="development"
                                        data-bs-parent="#courseList">
                                        <div class="p-md-4 p-2">
                                            <a href="#" class="btn btn-secondary btn-sm">Add Article +</a>
                                            <a href="#" class="btn btn-secondary btn-sm">Add Description +</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item rounded px-3 text-nowrap mb-1" id="project">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h5 class="mb-0 text-truncate">
                                            <a href="#" class="text-inherit">
                                                <i class="fe fe-menu me-1 align-middle"></i>
                                                <span class="align-middle">Hello World Project from GitHub</span>
                                            </a>
                                        </h5>
                                        <div>
                                            <a href="#" class="me-1 text-inherit" data-bs-toggle="tooltip"
                                                data-placement="top" aria-label="Edit" data-bs-original-title="Edit">
                                                <i class="fe fe-edit fs-6"></i>
                                            </a>
                                            <a href="#" class="me-1 text-inherit" data-bs-toggle="tooltip"
                                                data-placement="top" aria-label="Delete" data-bs-original-title="Delete">
                                                <i class="fe fe-trash-2 fs-6"></i>
                                            </a>
                                            <a href="#" class="text-inherit" data-bs-toggle="collapse"
                                                data-bs-target="#collapselistThree" aria-expanded="false"
                                                aria-controls="collapselistThree">
                                                <span class="chevron-arrow"><i class="fe fe-chevron-down"></i></span>
                                            </a>
                                        </div>
                                    </div>
                                    <div id="collapselistThree" class="collapse" aria-labelledby="project"
                                        data-bs-parent="#courseList">
                                        <div class="p-md-4 p-2">
                                            <a href="#" class="btn btn-secondary btn-sm">Add Article +</a>
                                            <a href="#" class="btn btn-secondary btn-sm">Add Description +</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item rounded px-3 text-nowrap mb-1" id="sample">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h5 class="mb-0 text-truncate">
                                            <a href="#" class="text-inherit">
                                                <i class="fe fe-menu me-1 align-middle"></i>
                                                <span class="align-middle">Our Sample Website</span>
                                            </a>
                                        </h5>
                                        <div>
                                            <a href="#" class="me-1 text-inherit" data-bs-toggle="tooltip"
                                                data-placement="top" aria-label="Edit" data-bs-original-title="Edit">
                                                <i class="fe fe-edit fs-6"></i>
                                            </a>
                                            <a href="#" class="me-1 text-inherit" data-bs-toggle="tooltip"
                                                data-placement="top" aria-label="Delete" data-bs-original-title="Delete">
                                                <i class="fe fe-trash-2 fs-6"></i>
                                            </a>
                                            <a href="#" class="text-inherit" data-bs-toggle="collapse"
                                                data-bs-target="#collapselistFour" aria-expanded="false"
                                                aria-controls="collapselistFour">
                                                <span class="chevron-arrow"><i class="fe fe-chevron-down"></i></span>
                                            </a>
                                        </div>
                                    </div>
                                    <div id="collapselistFour" class="collapse" aria-labelledby="sample"
                                        data-bs-parent="#courseList">
                                        <div class="p-md-4 p-2">
                                            <a href="#" class="btn btn-secondary btn-sm">Add Article +</a>
                                            <a href="#" class="btn btn-secondary btn-sm">Add Description +</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="#" class="btn btn-outline-primary btn-sm mt-3" data-bs-toggle="modal"
                            data-bs-target="#addLectureModal">Add Lecture +</a>
                    </div>
                    <div class="bg-light rounded p-2 mb-4">
                        <h4>JavaScript Beginnings</h4>

                        <!-- List group -->
                        <div class="list-group list-group-flush border-top-0" id="courseListSecond">
                            <div id="courseTwo">
                                <div class="list-group-item rounded px-3 text-nowrap mb-1" id="introductionSecond">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h5 class="mb-0 text-truncate">
                                            <a href="#" class="text-inherit">
                                                <i class="fe fe-menu me-1 align-middle"></i>
                                                <span class="align-middle">Introduction</span>
                                            </a>
                                        </h5>
                                        <div>
                                            <a href="#" class="me-1 text-inherit" data-bs-toggle="tooltip"
                                                data-placement="top" aria-label="Edit" data-bs-original-title="Edit">
                                                <i class="fe fe-edit fs-6"></i>
                                            </a>
                                            <a href="#" class="me-1 text-inherit" data-bs-toggle="tooltip"
                                                data-placement="top" aria-label="Delete" data-bs-original-title="Delete">
                                                <i class="fe fe-trash-2 fs-6"></i>
                                            </a>
                                            <a href="#" class="text-inherit" data-bs-toggle="collapse"
                                                data-bs-target="#collapselistFive" aria-expanded="false"
                                                aria-controls="collapselistFive">
                                                <span class="chevron-arrow"><i class="fe fe-chevron-down"></i></span>
                                            </a>
                                        </div>
                                    </div>
                                    <div id="collapselistFive" class="collapse" aria-labelledby="introductionSecond"
                                        data-bs-parent="#courseListSecond">
                                        <div class="p-md-4 p-2">
                                            <a href="#" class="btn btn-secondary btn-sm">Add Article +</a>
                                            <a href="#" class="btn btn-secondary btn-sm">Add Description +</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item rounded px-3 text-nowrap mb-1" id="developmentSecond">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h5 class="mb-0 text-truncate">
                                            <a href="#" class="text-inherit">
                                                <i class="fe fe-menu me-1 align-middle"></i>
                                                <span class="align-middle">Installing Development Software</span>
                                            </a>
                                        </h5>
                                        <div>
                                            <a href="#" class="me-1 text-inherit" data-bs-toggle="tooltip"
                                                data-placement="top" aria-label="Edit" data-bs-original-title="Edit">
                                                <i class="fe fe-edit fs-6"></i>
                                            </a>
                                            <a href="#" class="me-1 text-inherit" data-bs-toggle="tooltip"
                                                data-placement="top" aria-label="Delete" data-bs-original-title="Delete">
                                                <i class="fe fe-trash-2 fs-6"></i>
                                            </a>
                                            <a href="#" class="text-inherit" data-bs-toggle="collapse"
                                                data-bs-target="#collapselistSix" aria-expanded="false"
                                                aria-controls="collapselistSix">
                                                <span class="chevron-arrow"><i class="fe fe-chevron-down"></i></span>
                                            </a>
                                        </div>
                                    </div>
                                    <div id="collapselistSix" class="collapse" aria-labelledby="developmentSecond"
                                        data-bs-parent="#courseListSecond">
                                        <div class="p-md-4 p-2">
                                            <a href="#" class="btn btn-secondary btn-sm">Add Article +</a>
                                            <a href="#" class="btn btn-secondary btn-sm">Add Description +</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item rounded px-3 text-nowrap mb-1" id="projectSecond">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h5 class="mb-0 text-truncate">
                                            <a href="#" class="text-inherit">
                                                <i class="fe fe-menu me-1 align-middle"></i>
                                                <span class="align-middle">Hello World Project from GitHub</span>
                                            </a>
                                        </h5>
                                        <div>
                                            <a href="#" class="me-1 text-inherit" data-bs-toggle="tooltip"
                                                data-placement="top" aria-label="Edit" data-bs-original-title="Edit">
                                                <i class="fe fe-edit fs-6"></i>
                                            </a>
                                            <a href="#" class="me-1 text-inherit" data-bs-toggle="tooltip"
                                                data-placement="top" aria-label="Delete" data-bs-original-title="Delete">
                                                <i class="fe fe-trash-2 fs-6"></i>
                                            </a>
                                            <a href="#" class="text-inherit" data-bs-toggle="collapse"
                                                data-bs-target="#collapselistSeven" aria-expanded="false"
                                                aria-controls="collapselistSeven">
                                                <span class="chevron-arrow"><i class="fe fe-chevron-down"></i></span>
                                            </a>
                                        </div>
                                    </div>
                                    <div id="collapselistSeven" class="collapse" aria-labelledby="projectSecond"
                                        data-bs-parent="#courseListSecond">
                                        <div class="p-md-4 p-2">
                                            <a href="#" class="btn btn-secondary btn-sm">Add Article +</a>
                                            <a href="#" class="btn btn-secondary btn-sm">Add Description +</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item rounded px-3 text-nowrap mb-1" id="sampleSecond">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h5 class="mb-0 text-truncate">
                                            <a href="#" class="text-inherit">
                                                <i class="fe fe-menu me-1 align-middle"></i>
                                                <span class="align-middle">Our Sample Website</span>
                                            </a>
                                        </h5>
                                        <div>
                                            <a href="#" class="me-1 text-inherit" data-bs-toggle="tooltip"
                                                data-placement="top" aria-label="Edit" data-bs-original-title="Edit">
                                                <i class="fe fe-edit fs-6"></i>
                                            </a>
                                            <a href="#" class="me-1 text-inherit" data-bs-toggle="tooltip"
                                                data-placement="top" aria-label="Delete" data-bs-original-title="Delete">
                                                <i class="fe fe-trash-2 fs-6"></i>
                                            </a>
                                            <a href="#" class="text-inherit" data-bs-toggle="collapse"
                                                data-bs-target="#collapselistEight" aria-expanded="false"
                                                aria-controls="collapselistEight">
                                                <span class="chevron-arrow"><i class="fe fe-chevron-down"></i></span>
                                            </a>
                                        </div>
                                    </div>
                                    <div id="collapselistEight" class="collapse" aria-labelledby="sampleSecond"
                                        data-bs-parent="#courseListSecond">
                                        <div class="p-md-4 p-2">
                                            <a href="#" class="btn btn-secondary btn-sm">Add Article +</a>
                                            <a href="#" class="btn btn-secondary btn-sm">Add Description +</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="#" class="btn btn-outline-primary btn-sm mt-3" data-bs-toggle="modal"
                            data-bs-target="#addLectureModal">Add Lecture +</a>
                    </div>
                    <a href="#" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#addSectionModal">Add Section</a>
                </div>
            </div>
            <!-- Button -->
            <div class="d-flex justify-content-between">
                <button class="btn btn-secondary previous_btn">Previous</button>
                <button type="submit" class="btn btn-primary">Next</button>
            </div>
        </div>
    </form>

@endsection
@push('scripts')
    <script>
        const store_course_content_info_url = base_url + '/instructor/courses/update';

        $('.course_content_form').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                method: 'POST',
                url: store_course_content_info_url,
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {

                },
                success: function(data) {
                    if (data.status == 'success') {
                        iziToast.success({
                            message: data.message,
                            position: 'bottomRight'
                        });
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 1000);
                    }
                },
                error: function(xhr, status, error) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        value.forEach(function(message) {
                            iziToast.error({
                                message: message,
                                position: 'bottomRight'
                            });
                        });
                    });
                },
                complete: function() {

                }
            });
        });
    </script>
@endpush
