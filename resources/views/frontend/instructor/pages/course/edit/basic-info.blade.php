@extends('frontend.instructor.pages.course.course-master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('course-content')
    <!-- Content one -->
    <form enctype="multipart/form-data" class="update_basic_info_form course_form">
        @csrf
        {{-- HIDEN INPUT START --}}
        <input type="hidden" name="course_id" value="{{ request()?->id }}">
        <input type="hidden" name="current_step" value="1">
        <input type="hidden" name="next_step" value="2">
        {{-- HIDEN INPUT ENT --}}
        <div>
            <!-- Card -->
            <div class="card mb-3">
                <div class="card-header border-bottom px-4 py-3">
                    <h4 class="mb-0">Basic Information</h4>
                </div>
                <!-- Card body -->
                <div class="card-body">

                    <div class="mb-3">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" placeholder="e.g. Learn JavaScript in 30 Days"
                            id="title" name="title" value="{{ $course?->title }}">
                        <small>Write a 60 character course title.</small>
                    </div>

                    <div class="mb-3">
                        <label for="seo_description" class="form-label">SEO Description</label>
                        <input class="form-control" type="text" placeholder="e.g. Learn JavaScript in 30 Days"
                            id="seo_description" name="seo_description" value="{{ $course?->seo_description }}">
                        <small>Write a 160 character course SEO description.</small>
                    </div>

                    <div class="mb-3">
                        <label for="thumbnail" class="form-label">Thumbnail <span class="text-danger">*</span></label>
                        <input class="form-control" type="file" id="thumbnail" name="thumbnail">
                        <small>Upload a high-quality image that represents your course.</small>
                    </div>

                    <div class="mb-3">
                        <label for="demo_video_storage" class="form-label">Demo Video Storage</label>
                        <select class="form-select storage" id="demo_video_storage" name="demo_video_storage">
                            <option value="">Select storage option</option>
                            <option value="upload" @selected($course?->demo_video_storage == 'upload')>
                                Upload</option>
                            <option value="youtube" @selected($course?->demo_video_storage == 'youtube')>
                                Youtube</option>
                        </select>
                        <small>Select your video type for upload.</small>
                    </div>

                    <div class="mb-3 upload_source   {{ $course->demo_video_source == 'upload' ? '' : 'd-none' }}">
                        <label for="demo_video_source_upload" class="form-label">Demo Video Source</label>
                        <input class="form-control" type="file" id="demo_video_source_upload"
                            name="demo_video_source_upload" value="{{ $course?->demo_video_source }}">
                        <small>Upload a demo video that represents your course.</small>
                    </div>

                    <div class="mb-3 link_source {{ $course->demo_video_source != 'upload' ? '' : 'd-none' }}">
                        <label for="demo_video_source_link" class="form-label">Demo Video Source</label>
                        <input class="form-control" type="text" id="demo_video_source_link" name="demo_video_source_link"
                            placeholder="Please provide link here." value="{{ $course?->demo_video_source }}">
                        <small>Enter a valid video URL. Students who watch a well-made promo video are 5X more likely to
                            enroll in your course.</small>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                        <input class="form-control" type="number" id="price" name="price" placeholder="e.g. 9.99"
                            value="{{ $course?->price }}">
                        <small>Set a price for your course. You can always change it later.</small>
                    </div>

                    <div class="mb-3">
                        <label for="discount" class="form-label">Discount (%)</label>
                        <input class="form-control" type="number" id="discount" name="discount" placeholder="e.g. 10"
                            value="{{ $course?->discount }}">
                        <small>Set a discount percentage for your course. You can always change it later.</small>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="description" name="description" rows="5"
                            placeholder="Write a detailed description of your course.">{!! $course?->description !!}</textarea>
                        <small>Write a detailed description of your course. This will help students understand what your
                            course is about and what they will learn.</small>
                    </div>
                </div>
            </div>
            <!-- Button -->
            <button type="submit" class="btn btn-primary">Next</button>
        </div>
    </form>

@endsection
@push('scripts')
    <script>
        const update_course_basic_info_url = base_url + '/instructor/courses/update';

        $('.update_basic_info_form').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                method: 'POST',
                url: update_course_basic_info_url,
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


        $('.storage').on('change', function() {
            let storage_val = $(this).val();
            $('.inps_path').val('');
            if (storage_val == 'upload') {
                $('.upload_source').removeClass('d-none');
                $('.link_source').addClass('d-none');
            } else if (storage_val == 'youtube') {
                $('.upload_source').addClass('d-none');
                $('.link_source').removeClass('d-none');
            } else {
                $('.upload_source').addClass('d-none');
                $('.link_source').addClass('d-none');
            }
        });
    </script>
@endpush
