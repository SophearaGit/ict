<form action="{{ route('admin.courses.realtime.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h5 class="modal-title">Add New Course</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <!-- Modal Body -->
        <div class="modal-body">
            <div class="row">
                <div class="col-12 mb-3">
                    <label for="title" class="form-label">Title <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="title" id="title" class="form-control"
                        placeholder="Enter course title" required>
                </div>
            </div>

            <div class="row">
                <div class="col-12 mb-3">
                    <label class="form-label">Schedule <span class="text-danger">*</span></label>
                    <select name="schedule_id" class="form-select schedule-select2">
                        <option value="">Select Schedule</option>
                        @foreach ($schedules_for_select as $day => $schedules)
                            <optgroup label="{{ ucfirst($day) }}">
                                @foreach ($schedules as $schedule)
                                    <option value="{{ $schedule->id }}">
                                        {{ $schedule->shift_label }}
                                        ({{ $schedule->formatted_time }})
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
            </div>
            {{-- price --}}
            <div class="row">
                <div class="col-12 mb-3">
                    <label for="price" class="form-label">Price ($) <span class="text-danger">*</span></label>
                    <input type="number" name="price" id="price" class="form-control"
                        placeholder="Enter course price" required min="0" step="0.01">
                </div>
            </div>
            {{-- instructor --}}
            <div class="row">
                <div class="col-12 mb-3">
                    <label for="instructor_id" class="form-label">Instructor <span class="text-danger">*</span></label>
                    <select name="instructor_id" id="instructor_id" class="form-select">
                        <option value="">Select Instructor</option>
                        @foreach ($instructors as $instructor)
                            <option value="{{ $instructor->id }}">{{ $instructor->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            {{-- status --}}
            <div class="row">
                <div class="col-12 mb-3">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select">
                        <option value="active">Open</option>
                        <option value="inactive">Close</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-12 mb-3">
                    <label for="thumbnail" class="form-label">Thumbnail</label>
                    <input type="file" name="thumbnail" id="thumbnail" class="form-control">
                </div>
            </div>

            {{-- text area tiny mce --}}
            <div class="row">
                <div class="col-12 mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="4"
                        placeholder="Enter course description"></textarea>
                </div>
            </div>





        </div>

        <!-- Modal Footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                Cancel
            </button>

            <button type="submit" class="btn btn-primary">
                Create Course
            </button>
        </div>

    </div>
</form>

<script>
    $('#addCourseModal').on('shown.bs.modal', function() {

        // Select2
        $(this).find('.schedule-select2').select2({
            dropdownParent: $('#addCourseModal'),
            width: '100%',
            placeholder: "Select Schedule"
        });

        // TinyMCE init (prevent duplicate)
        if (!tinymce.get('description')) {
            tinymce.init({
                selector: '#description',
                height: 300,
                menubar: false,
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap',
                    'preview', 'anchor', 'searchreplace', 'visualblocks',
                    'code', 'fullscreen', 'insertdatetime', 'media', 'table',
                    'help', 'wordcount'
                ],
                toolbar: `
                undo redo | formatselect |
                bold italic backcolor |
                alignleft aligncenter alignright alignjustify |
                bullist numlist outdent indent |
                removeformat | help
            `
            });
        }
    });

    $('#addCourseModal').on('shown.bs.modal', function() {

        // Select2
        $(this).find('.schedule-select2').select2({
            dropdownParent: $('#addCourseModal'),
            width: '100%',
            placeholder: "Select Schedule"
        });

        // TinyMCE init (prevent duplicate)
        if (!tinymce.get('description')) {
            tinymce.init({
                selector: '#description',
                height: 300,
                menubar: false,
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap',
                    'preview', 'anchor', 'searchreplace', 'visualblocks',
                    'code', 'fullscreen', 'insertdatetime', 'media', 'table',
                    'help', 'wordcount'
                ],
                toolbar: `
                undo redo | formatselect |
                bold italic backcolor |
                alignleft aligncenter alignright alignjustify |
                bullist numlist outdent indent |
                removeformat | help
            `
            });
        }
    });

    $('#addCourseModal').on('hidden.bs.modal', function() {
        if (tinymce.get('description')) {
            tinymce.get('description').remove();
        }
    });
</script>
