<div class="modal-content">
    <form
        action="
            {{ @$on_edit == true
                ? route('instructor.course-content.update-chapter', $chapter_id)
                : route('instructor.course-content.store-chapter', $course_id) }}
            "
        method="POST">
        @csrf
        <div class="modal-header">
            <h4 class="modal-title" id="ChapterModalLabel">
                {{ @$on_edit == true ? 'Edit' : 'Add' }} New Chapter</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3 ">
                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title" value="{{ @$chapter?->title }}"
                    placeholder="Enter chapter title" required>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="submit">{{ @$on_edit == true ? 'Update' : 'Save' }}</button>
            <button class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
        </div>
    </form>
</div>
