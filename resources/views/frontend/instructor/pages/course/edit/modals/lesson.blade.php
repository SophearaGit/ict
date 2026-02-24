<style>
    /* Beautiful duration range */

    .duration-range {
        height: 6px;
        border-radius: 10px;
        background: linear-gradient(to right,
                var(--bs-primary) 0%,
                var(--bs-primary) var(--range-progress, 0%),
                #dee2e6 var(--range-progress, 0%),
                #dee2e6 100%);
    }

    .duration-range::-webkit-slider-thumb {
        width: 18px;
        height: 18px;
        /* background-color: var(--gk-btn-bg); */
        border-radius: 50%;
        cursor: pointer;
        border: 2px solid #fff;
        margin-top: -6px;
    }

    .duration-range::-moz-range-thumb {
        width: 18px;
        height: 18px;
        /* background-color: var(--gk-btn-bg); */
        border-radius: 50%;
        cursor: pointer;
        border: 2px solid #fff;
    }
</style>
<div class="modal-content">
    <form
        action="{{ @$on_edit == true
            ? route('instructor.course-content.update-lesson', @$lesson?->id)
            : route('instructor.course-content.store-lesson') }}"
        method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
            <h4 class="modal-title" id="LessonModalLabel">
                {{ @$on_edit == true ? 'Edit Lesson' : 'Add New Lesson' }}
            </h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

            {{-- START HIDDEN INPUT --}}
            <input type="hidden" name="course_id" value="{{ $course_id }}">
            <input type="hidden" name="chapter_id" value="{{ $chapter_id }}">
            {{-- END HIDDEN INPUT  --}}

            <div class="mb-3">
                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                <input class="form-control mb-3" type="text" placeholder="e.g. Introduction to JavaScript"
                    id="title" name="title" value="{{ @$lesson?->title }}">
            </div>

            {{-- description --}}
            <div class="mb-3">
                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                <textarea class="form-control" id="description" name="description" rows="3"
                    placeholder="Write a short description about this lesson...">{!! @$lesson?->description !!}</textarea>
            </div>

            {{-- source select loop --}}
            <div class="mb-3">
                <label for="storage" class="form-label">Source <span class="text-danger">*</span></label>
                <select class="form-select storage" name="storage" required>
                    <option value="">
                        Select source
                    </option>
                    @foreach (config('course.video_source') as $item => $name)
                        <option value="{{ $item }}" @selected($item == @$lesson?->storage)>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3 upload_source {{ @$lesson?->storage == 'upload' ? '' : 'd-none' }}">
                <label for="file_path" class="form-label">
                    File Path
                    <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-btn">
                        <a id="lfm" data-input="thumbnail_lfm" data-preview="holder" class="btn btn-primary">
                            <i class="fa fa-picture-o"></i> Choose
                        </a>
                    </span>
                    <input id="thumbnail_lfm" class="form-control inps_path" type="text" name="file_path"
                        value="{{ @$lesson?->file_path }}">
                </div>
            </div>

            <div class="mb-3 link_source {{ @$lesson?->storage == 'upload' ? 'd-none' : '' }}">
                <label for="url" class="form-label">
                    URL
                    <span class="text-danger">*</span>
                </label>
                <input class="form-control inps_path" type="text" name="url"
                    placeholder="e.g. https://www.youtube.com/watch?v=dQw4w9WgXcQ" value="{{ @$lesson?->file_path }}">
            </div>

            <div class="mb-3">
                <label for="file_type" class="form-label">File Type <span class="text-danger">*</span></label>
                <select class="form-select file_type" name="file_type" required>
                    <option value="">Select file type</option>
                    @foreach (config('course.file_types') as $item => $name)
                        <option value="{{ $item }}" @selected(@$lesson?->file_type == $item)>{{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4 duration_container">
                <label for="duration" class="form-label">
                    Duration (in minutes)
                </label>
                <div class="d-flex align-items-center gap-3">
                    <input type="range" class="form-range  duration-range" id="duration" name="duration"
                        min="1" max="30" step="1" value="{{ @$lesson?->duration ?? 0 }}">

                    <span class="badge bg-primary px-3 py-2 duration-value">
                        {{ @$lesson?->duration ?? 0 }} min
                    </span>
                </div>
            </div>

            <div class="mb-3 form-check form-switch is_preview_container">
                <input class="form-check-input" type="checkbox" role="switch" id="is_preview" name="is_preview"
                    value="1" @checked(@$lesson?->is_preview)>
                <label class="form-check-label" for="is_preview">
                    Free to watch as preview
                </label>
            </div>

        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="submit">
                <i class="fa-solid fa-plus"></i>
                {{ @$on_edit == true ? 'Update Lesson' : 'Add Lesson' }}
            </button>
            <button class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
        </div>
    </form>
</div>

<script>
    $('#lfm').filemanager('file');

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

    // $('.file_type').on('change', function() {
    //     let file_type_val = $(this).val();

    //     $('#duration').val(1);

    //     durationRange.value = 1;
    //     updateDuration();

    //     if (file_type_val == 'video') {
    //         $('.duration_container').removeClass('d-none');
    //         $('.is_preview_container').removeClass('d-none');
    //     } else if (file_type_val == 'audio') {
    //         $('.duration_container').removeClass('d-none');
    //         $('.is_preview_container').addClass('d-none');
    //     } else {
    //         $('.duration_container').addClass('d-none');
    //         $('.is_preview_container').addClass('d-none');
    //     }
    // });

    const durationRange = document.getElementById('duration');
    const durationValue = document.querySelector('.duration-value');

    function updateDuration() {
        const value = durationRange.value;
        const max = durationRange.max;
        const progress = (value / max) * 100;

        durationRange.style.setProperty('--range-progress', `${progress}%`);
        durationValue.textContent = `${value} min`;
    }

    // Init
    updateDuration();

    durationRange.addEventListener('input', updateDuration);
</script>
