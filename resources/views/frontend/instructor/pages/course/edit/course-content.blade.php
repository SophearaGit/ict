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
                    <h4 class="mb-0">
                        Course Content
                    </h4>
                </div>
                <!-- Card body -->
                <div class="card-body">
                    @forelse ($chapters as $chapter)
                        <div class="bg-light rounded p-2 mb-4 ">
                            <div class="d-flex  align-items-center justify-content-between mb-2">
                                <h4>{{ $chapter->title }}</h4>
                                <div>
                                    <a href="javascript:;" class="me-1 text-inherit add_lesson_btn" data-placement="top"
                                        data-bs-toggle="tooltip" aria-label="Add" data-bs-original-title="Add Lesson"
                                        data-course-id="{{ $course_id }}" data-chapter-id="{{ $chapter->id }}">
                                        <i class="fe fe-plus-circle fs-6"></i>
                                    </a>
                                    <a href="javascript:;" class="me-1 text-inherit edit_chapter_btn" data-placement="top"
                                        data-bs-toggle="tooltip" aria-label="Edit" data-bs-original-title="Edit Chapter"
                                        data-course-id="{{ $course_id }}" data-chapter-id="{{ $chapter->id }}">
                                        <i class="fe
                                        fe-edit fs-6"></i>
                                    </a>
                                    <a href="{{ route('instructor.course-content.delete-chapter', $chapter->id) }}"
                                        class="me-1 text-inherit dynamic_delete_btn" data-bs-toggle="tooltip"
                                        data-placement="top" aria-label="Delete" data-bs-original-title="Delete Chapter">
                                        <i class="fe fe-trash-2 fs-6"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="list-group list-group-flush border-top-0">
                                <ul class="list-unstyled sortable_list mb-0">
                                    @forelse ($chapter->lessons ?? [] as $lesson)
                                        <li class="list-group-item rounded px-3 text-nowrap mb-1"
                                            data-lesson-id="{{ $lesson->id }}" data-chapter-id="{{ $chapter->id }}">
                                            <div class="d-flex align-items-center justify-content-between dragger"
                                                style="cursor: move">
                                                <h5 class="mb-0 text-truncate">
                                                    <a href="#" class="text-inherit" style="cursor: move">
                                                        <i class="fe fe-menu me-1 align-middle"></i>
                                                        <span class="align-middle">
                                                            {{ $lesson->title }}
                                                        </span>
                                                    </a>
                                                </h5>
                                                <div>
                                                    <a href="javascript:;" class="me-1 text-inherit edit_lesson_btn"
                                                        data-bs-toggle="tooltip" data-placement="top"
                                                        aria-label="Edit Lesson" data-bs-original-title="Edit Lesson"
                                                        data-course-id="{{ $course_id }}"
                                                        data-chapter-id="{{ $chapter->id }}"
                                                        data-lesson-id="{{ $lesson->id }}">
                                                        <i class="fe fe-edit fs-6"></i>
                                                    </a>
                                                    <a class="me-1 text-inherit dynamic_delete_btn"
                                                        href="{{ route('instructor.course-content.delete-lesson', $lesson->id) }}"
                                                        data-bs-toggle="tooltip" data-placement="top" aria-label="Delete"
                                                        data-bs-original-title="Delete">
                                                        <i class="fe fe-trash-2 fs-6"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="text-muted">No lessons added yet.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    @empty
                        <p>No chapters added yet.</p>
                    @endforelse
                    <a href="#" class="btn btn-outline-primary btn-sm add_chapter_btn"
                        data-id="{{ request()->id }}">Add
                        Chapter</a>
                </div>
            </div>
            <!-- Button -->
            <div class="d-flex justify-content-between">
                <button class="btn btn-secondary previous_btn" data-step="{{ request()->step }}">Previous</button>
                <button type="submit" class="btn btn-primary">Next</button>
            </div>
        </div>
    </form>

    <!-- Chapter Modal -->
    <div class="modal fade" id="dynamic_chapter_modal" tabindex="-1" role="dialog" aria-labelledby="ChapterModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered dynamic_modal_content">
        </div>
    </div>

    <!-- Lesson Modal -->
    <div class="modal fade" id="dynamic_lesson_modal" tabindex="-1" role="dialog" aria-labelledby="LessonModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered dynamic_lesson_modal_content">
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        let loader = `
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="ChapterModalLabel">
                        Loading...
                        </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 align-items-center justify-content-center d-flex">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit" disabled>Save</button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close" disabled>Close</button>
                </div>
            </div>
        `;

        $('.add_chapter_btn').on('click', function(e) {
            e.preventDefault();
            $('#dynamic_chapter_modal').modal('show');
            let course_id = $(this).data('id');
            $.ajax({
                method: 'GET',
                url: base_url + `/instructor/course-content/${course_id}/create-chapter`,
                data: {},
                beforeSend: function() {
                    $('.dynamic_modal_content').html(loader);
                },
                success: function(data) {
                    $('.dynamic_modal_content').html(data);
                },
                error: function(xhr, status, error) {

                },
            })
        })

        $('.edit_chapter_btn').on('click', function() {
            $('#dynamic_chapter_modal').modal('show');
            let course_id = $(this).data('course-id');
            let chapter_id = $(this).data('chapter-id');
            let get_edit_chapter_url = base_url + '/instructor/course-content/:course_id/edit-chapter';

            $.ajax({
                method: 'GET',
                url: get_edit_chapter_url.replace('course_id', course_id),
                data: {
                    'course_id': course_id,
                    'chapter_id': chapter_id,
                },
                beforeSend: function() {
                    $('.dynamic_modal_content').html(loader);
                },
                success: function(data) {
                    $('.dynamic_modal_content').html(data);
                },
                error: function(xhr, status, error) {

                },
            })
        })

        $('.add_lesson_btn').on('click', function(e) {
            e.preventDefault();
            $('#dynamic_lesson_modal').modal('show');
            let course_id = $(this).data('course-id');
            let chapter_id = $(this).data('chapter-id');

            $.ajax({
                method: 'GET',
                url: base_url + '/instructor/course-content/create-lesson',
                data: {
                    'course_id': course_id,
                    'chapter_id': chapter_id,
                },
                beforeSend: function() {
                    $('.dynamic_lesson_modal_content').html(loader);
                },
                success: function(data) {
                    $('.dynamic_lesson_modal_content').html(data);
                },
                error: function(xhr, status, error) {

                },
            })
        })

        $('.edit_lesson_btn').on('click', function() {
            $('#dynamic_lesson_modal').modal('show');

            let course_id = $(this).data('course-id');
            let chapter_id = $(this).data('chapter-id');
            let lesson_id = $(this).data('lesson-id');

            $.ajax({
                method: 'GET',
                url: base_url + '/instructor/course-content/edit-lesson',
                data: {
                    'course_id': course_id,
                    'chapter_id': chapter_id,
                    'lesson_id': lesson_id,
                },
                beforeSend: function() {
                    $('.dynamic_lesson_modal_content').html(loader);
                },
                success: function(data) {
                    $('.dynamic_lesson_modal_content').html(data);
                },
                error: function(xhr, status, error) {

                },
            })
        })

        $('.dynamic_delete_btn').on('click', function(e) {
            e.preventDefault();
            let url = $(this).attr('href');
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        method: "DELETE",
                        url: url,
                        data: {
                            _token: csrf_token
                        },
                        success: function(data) {
                            if (data.status == 'success') {
                                iziToast.success({
                                    message: data.message,
                                    position: 'bottomRight'
                                });
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1000);
                            }
                        },
                        error: function(xhr, status, data) {},
                    })
                }
            });
        })

        $('.course_content_form').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                method: 'POST',
                url: base_url + '/instructor/courses/update',
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

        if ($('.sortable_list li').length) {
            $('.sortable_list').sortable({
                items: "li",
                containment: "parent",
                cursor: "move",
                handle: ".dragger",
                update: function(event, ui) {
                    let order_ids = $(this).sortable("toArray", {
                        attribute: "data-lesson-id"
                    })
                    let chapter_ids = ui.item.data("chapter-id")
                    $.ajax({
                        method: "POST",
                        url: base_url + `/instructor/course-chapter/${chapter_ids}/sort-lesson`,
                        data: {
                            _token: csrf_token,
                            order_ids: order_ids,
                        },
                        success: function(data) {
                            if (data.status == 'success') {
                                iziToast.success({
                                    message: data.message,
                                    position: 'bottomRight'
                                });
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
                    })
                }
            });
        }
    </script>
@endpush
