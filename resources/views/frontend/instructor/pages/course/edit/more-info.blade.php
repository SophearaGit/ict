@extends('frontend.instructor.pages.course.course-master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('course-content')
    <!-- Content one -->
    <form class="more_info_form course_form">
        @csrf
        {{-- HIDEN INPUT START --}}
        <input type="hidden" name="course_id" value="{{ request()?->id }}">
        <input type="hidden" name="current_step" value="2">
        <input type="hidden" name="next_step" value="3">
        {{-- HIDEN INPUT ENT --}}
        <div>
            <!-- Card -->
            <div class="card mb-3">
                <div class="card-header border-bottom px-4 py-3">
                    <h4 class="mb-0">More Information</h4>
                </div>
                <!-- Card body -->
                <div class="card-body">
                    {{-- capacity --}}
                    <div class="mb-3">
                        <label for="capacity" class="form-label">Capacity</label>
                        <input class="form-control" type="number" placeholder="e.g. 100" id="capacity" name="capacity"
                            value="{{ $course?->capacity }}">
                        <small>Set a maximum number of students who can enroll in the course.</small>
                    </div>
                    {{-- duration in minutes --}}
                    <div class="mb-3">
                        <label for="duration" class="form-label">Duration (in minutes) <span
                                class="text-danger">*</span></label>
                        <input class="form-control" type="number" placeholder="e.g. 120" id="duration" name="duration"
                            value="{{ $course?->duration }}">
                        <small>Set the total duration of the course in minutes.</small>
                    </div>
                    {{-- qna checkbox --}}
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="qna" name="qna" value="1"
                            @checked($course?->qna === 1)>
                        <label class="form-check-label" for="qna">Enable Q&A</label>
                        <small>Allow students to ask questions and interact with the instructor.</small>
                    </div>
                    {{-- certificate checkbox --}}
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="certificate" name="certificate" value="1"
                            @checked($course?->certificate === 1)>
                        <label class="form-check-label" for="certificate">Enable Certificate</label>
                        <small>Provide a certificate of completion to students who finish the course.</small>
                    </div>
                    {{-- category --}}
                    <div class="mb-3">
                        <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                        <select class="form-select" id="category" name="category">
                            <option value="">Select category</option>
                            @foreach ($categories as $category)
                                @if ($category->subCategories->isNotEmpty())
                                    <optgroup label="{{ $category->name }}">
                                        @foreach ($category->subCategories as $subCategory)
                                            <option @selected($course?->category_id == $subCategory->id) value="{{ $subCategory->id }}">
                                                {{ $subCategory->name }}</option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            @endforeach
                        </select>
                        <small>Choose the most relevant category for your course to help students find it.</small>
                    </div>
                    {{-- languages --}}
                    <div class="mb-3">
                        <label for="language" class="form-label">Language</label>
                        <select class="form-select" id="language" name="language">
                            <option value="">Select language</option>
                            @foreach ($languages as $language)
                                <option @selected($course?->course_language_id == $language->id) value="{{ $language->id }}">{{ $language->name }}
                                </option>
                            @endforeach
                        </select>
                        <small>Select the primary language of instruction for your course.</small>
                    </div>
                    {{-- levels loop --}}
                    <div class="mb-3">
                        <label for="level" class="form-label">Level</label>
                        <select class="form-select" id="level" name="level">
                            <option value="">Select level</option>
                            @foreach ($levels as $level)
                                <option @selected($course?->course_level_id == $level->id) value="{{ $level->id }}">{{ $level->name }}
                                </option>
                            @endforeach
                        </select>
                        <small>Indicate the difficulty level of your course (e.g., Beginner, Intermediate,
                            Advanced).</small>
                    </div>
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
        const store_course_more_info_url = base_url + '/instructor/courses/update';

        $('.more_info_form').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                method: 'POST',
                url: store_course_more_info_url,
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
