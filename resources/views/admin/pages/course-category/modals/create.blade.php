<div class="modal fade" id="newCategory" tabindex="-1" aria-labelledby="newCategoryLabel" style="display: none;"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title mb-0" id="newCategoryLabel">Create New Category</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.course-category.store') }}" method="post" class="needs-validation"
                    novalidate="" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="categoryName">
                            Name
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" placeholder="Enter category name here."
                            id="categoryName" required="" name="name">
                        <div class="invalid-feedback">Please enter category name.</div>
                    </div>

                    {{-- <div class="mb-3">
                        <label class="form-label" for="categoryImage">
                            Image
                        </label>
                        <input type="file" class="form-control" id="categoryImage" name="image" accept="image/*">
                        <div class="invalid-feedback">Please select an image.</div>
                    </div>

                    <div class="mb-3 aling-self-center">
                        <img id="categoryImagePreview" src="#" alt="Image Preview"
                            style="max-width: 200px; display: none;">
                    </div>

                    <script>
                        $('#categoryImage').on('change', function(e) {
                            const file = e.target.files[0];
                            if (file) {
                                const reader = new FileReader();
                                reader.onload = function(event) {
                                    $('#categoryImagePreview').attr('src', event.target.result).show();
                                };
                                reader.readAsDataURL(file);
                            }
                        });
                    </script> --}}

                    {{-- <div class="mb-3">
                        <label class="form-label" for="categoryIcon">
                            Icon Name
                        </label>
                        <input type="text" class="form-control" placeholder="Enter icon name here." id="categoryIcon"
                            name="icon">
                        <div class="invalid-feedback">Please enter icon name.</div>
                    </div> --}}

                    @if (count($course_categories_parent_for_select) > 0)
                        <div class="mb-4">
                            <label class="form-label" for="parentCategory">Parent Category</label>
                            <select class="form-select" id="parentCategory" name="parent_id">
                                <option value="">Select parent category (optional)</option>
                                @foreach ($course_categories_parent_for_select->unique('id') as $parentCategory)
                                    <option value="{{ $parentCategory->id }}">{{ $parentCategory->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="mb-4">
                        <label class="form-label">Trending</label>
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="show_at_trending"
                                name="show_at_trending" value="1">
                            <label class="form-check-label" for="show_at_trending"></label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Status</label>
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="status" name="status" checked=""
                                value="1">
                            <label class="form-check-label" for="status"></label>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="btn btn-primary">Add New Category</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
