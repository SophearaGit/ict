<!-- Edit Course Category Modal -->
<div class="modal fade" id="editCourseCategory" tabindex="-1" aria-labelledby="editCourseCategoryLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <!-- Header -->
            <div class="modal-header">
                <h4 class="modal-title mb-0" id="editCourseCategoryLabel">
                    Edit Course Category
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <form id="editCourseCategoryForm" method="POST" enctype="multipart/form-data" class="needs-validation"
                    novalidate>
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div class="mb-3">
                        <label class="form-label">
                            Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="categoryName" name="name"
                            placeholder="Enter category name" required>
                        <div class="invalid-feedback">
                            Please enter category name.
                        </div>
                    </div>

                    {{-- <!-- Image -->
                    <div class="mb-3">
                        <label class="form-label">Image</label>
                        <input type="file" class="form-control" id="categoryImage" name="image" accept="image/*">
                    </div>

                    <!-- Image Preview -->
                    <div class="mb-3 text-center">
                        <img id="categoryImagePreview" src="" alt="Image Preview"
                            style="max-width:200px; display:none;" class="img-thumbnail">
                    </div> --}}

                    {{-- <!-- Icon -->
                    <div class="mb-3">
                        <label class="form-label">Icon Name</label>
                        <input type="text" class="form-control" id="categoryIcon" name="icon"
                            placeholder="Enter icon name">
                    </div> --}}



                    <!-- Parent Category -->
                    <div class="mb-4" id="isParent">
                        <label class="form-label">Parent Category</label>
                        <select class="form-select" id="parentCategory" name="parent_id">
                            <option value="">Select parent category (optional)</option>
                            @foreach ($course_categories_parent_for_select as $parentCategory)
                                <option value="{{ $parentCategory->id }}">
                                    {{ $parentCategory->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <!-- Trending -->
                    <div class="mb-4">
                        <label class="form-label">Trending</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="show_at_trending"
                                name="show_at_trending" value="1">
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label class="form-label">Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="status" name="status"
                                value="1">
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            Update Course Category
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Preview image when selecting new one
    $('#categoryImage').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#categoryImagePreview')
                    .attr('src', e.target.result)
                    .show();
            };
            reader.readAsDataURL(file);
        }
    });

    // Populate modal on open
    $(document).on('show.bs.modal', '#editCourseCategory', function(e) {
        let button = $(e.relatedTarget);

        let id = button.data('id');
        let name = button.data('name');
        let icon = button.data('icon');
        let parent = button.data('parent');
        let trending = button.data('show_at_trending');
        let status = button.data('status');
        let image = button.data('image');

        let modal = $(this);

        // Set form action
        modal.find('#editCourseCategoryForm')
            .attr('action', `course-category/${id}`);

        // Set values
        modal.find('#categoryName').val(name);
        modal.find('#categoryIcon').val(icon);
        modal.find('#show_at_trending').prop('checked', trending == 1);
        modal.find('#status').prop('checked', status == 1);

        // Reset & handle parent category
        let parentSelect = modal.find('#parentCategory');
        parentSelect.val(parent ?? '');

        // 🔥 IMPORTANT PART: hide self-category
        parentSelect.find('option').each(function() {
            if ($(this).val() == id) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });

        // Image preview
        if (image) {
            modal.find('#categoryImagePreview')
                .attr('src', image)
                .show();
        } else {
            modal.find('#categoryImagePreview').hide();
        }
    });

    // Reset modal on close
    $('#editCourseCategory').on('hidden.bs.modal', function() {
        let modal = $(this);
        modal.find('form')[0].reset();
        modal.find('#parentCategory option').show(); // restore hidden options
        $('#categoryImagePreview').hide();
    });
</script>
