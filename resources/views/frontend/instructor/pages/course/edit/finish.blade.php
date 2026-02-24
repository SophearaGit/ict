@extends('frontend.instructor.pages.course.course-master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('course-content')
    <!-- Content one -->
    <form class="finish_form course_form">
        @csrf
        {{-- HIDEN INPUT START --}}
        <input type="hidden" name="course_id" value="{{ request()?->id }}">
        <input type="hidden" name="current_step" value="4">
        <input type="hidden" name="next_step" value="4">
        <input type="hidden" name="submit_for_review_check" id="submit_for_review_check" value="0">
        {{-- HIDEN INPUT ENT --}}
        <div>
            <!-- Card -->
            <div class="card mb-3 border-0">
                <div class="card-header border-bottom px-4 py-3">
                    <h4 class="mb-0">
                        Finish & Submit For Review
                    </h4>
                </div>
                <!-- Card body -->
                <div class="card-body">
                    <div class="mb-3">
                        <label for="message_for_reviewer" class="form-label">Message For Reviewer</label>
                        <textarea class="form-control" id="message_for_reviewer" name="message_for_reviewer" rows="3"
                            placeholder="Write a message for reviewer...">{!! @$course->message_for_reviewer !!}</textarea>
                        <small>Write a message for reviewer if you have any specific instruction or note regarding the
                            course.</small>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Course Status <span class="text-danger">*</span></label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Select status</option>
                            <option value="draft" @selected($course?->status == 'draft')>Draft</option>
                            <option value="active" @selected($course?->status == 'active')>Active</option>
                            <option value="inactive" @selected($course?->status == 'inactive')>Inactive</option>
                        </select>
                        <small>Select the course status. Active courses will be visible to students, while draft and
                            inactive courses will not.</small>
                    </div>
                </div>
            </div>
            <!-- Button -->
            <div class="d-flex justify-content-between mb-8">
                <!-- Button -->
                <button class="btn btn-secondary previous_btn" data-step="{{ request()->step }}">Previous</button>
                <button type="button" class="btn btn-danger" id="submitForReviewBtn">
                    Submit For Review
                </button>
            </div>
        </div>
    </form>
@endsection
@push('scripts')
    <script>
        $('#submitForReviewBtn').on('click', function() {
            // set value first
            $('#submit_for_review_check').val(1);

            $('.finish_form').trigger('submit');
        });

        $('.finish_form').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                method: 'POST',
                url: base_url + '/instructor/courses/update',
                data: formData,
                contentType: false,
                processData: false,
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
