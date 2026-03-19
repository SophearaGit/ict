<form id="editCourseForm" method="POST" action="{{ route('admin.courses.realtime.update', $course->id) }}"
    enctype="multipart/form-data">

    @csrf
    @method('PUT')

    <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h5 class="modal-title">Edit Course</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <!-- Modal Body -->
        <div class="modal-body">

            {{-- Title --}}
            <div class="mb-3">
                <label class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $course->title) }}">
            </div>

            {{-- Schedule --}}
            <div class="mb-3">
                <label class="form-label">Schedule <span class="text-danger">*</span></label>
                <select name="schedule_id" class="form-select schedule-select2">
                    <option value="">Select Schedule</option>

                    @foreach ($schedules_for_select as $day => $schedules)
                        <optgroup label="{{ ucfirst($day) }}">
                            @foreach ($schedules as $schedule)
                                <option value="{{ $schedule->id }}"
                                    {{ old('schedule_id', $course->schedule_id) == $schedule->id ? 'selected' : '' }}>
                                    {{ $schedule->shift_label }} ({{ $schedule->formatted_time }})
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>

            {{-- Price --}}
            <div class="mb-3">
                <label class="form-label">Price ($) <span class="text-danger">*</span></label>
                <input type="number" name="price" class="form-control" value="{{ old('price', $course->price) }}">
            </div>

            {{-- Instructor --}}
            <div class="mb-3">
                <label class="form-label">Instructor <span class="text-danger">*</span></label>
                <select name="instructor_id" class="form-select">
                    <option value="">Select Instructor</option>

                    @foreach ($instructors as $instructor)
                        <option value="{{ $instructor->id }}"
                            {{ old('instructor_id', $course->instructor_id) == $instructor->id ? 'selected' : '' }}>
                            {{ $instructor->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Status --}}
            <div class="mb-3">
                <label class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select">
                    <option value="active" {{ old('status', $course->status) == 'active' ? 'selected' : '' }}>
                        Open
                    </option>
                    <option value="inactive" {{ old('status', $course->status) == 'inactive' ? 'selected' : '' }}>
                        Close
                    </option>
                </select>
            </div>

            {{-- Thumbnail --}}
            <div class="mb-3">
                <label class="form-label">Thumbnail</label>
                <input type="file" name="thumbnail" class="form-control">

                @if ($course->thumbnail)
                    <img src="{{ asset($course->thumbnail) }}" class="mt-2" width="100">
                @endif
            </div>

            {{-- Description --}}
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" id="edit_description" class="form-control">{{ old('description', $course->description) }}</textarea>
            </div>

        </div>

        <!-- Footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                Cancel
            </button>
            <button type="submit" class="btn btn-primary">
                Update Course
            </button>
        </div>

    </div>
</form>

<script>
    tinymce.init({
        selector: '#edit_description',
        setup: function(editor) {
            editor.on('init', function() {
                editor.setContent(`{!! old('description', $course->description) !!}`);
            });
        }
    });
</script>
