<div class="modal fade" id="editCourseLevel" tabindex="-1" aria-labelledby="editCourseLevelLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title mb-0" id="editCourseLevelLabel">Edit Course Level</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="editCourseLevelForm" method="post" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_level_name" name="name" required>
                        <div class="invalid-feedback">Please enter course level.</div>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Course Level</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).on('show.bs.modal', '#editCourseLevel', function(e) {
        let button = $(e.relatedTarget);

        let id = button.data('id');
        let name = button.data('name');

        let modal = $(this);

        modal.find('#editCourseLevelForm').attr('action', `course-level/${id}`);
        modal.find('#edit_level_name').val(name);
    });
</script>
