<div class="modal fade" id="newLevel" tabindex="-1" aria-labelledby="newLevelLabel" style="display: none;"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title mb-0" id="newLevelLabel">Create New Level</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.course-level.store') }}" method="post" class="needs-validation"
                    novalidate="">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="levelName">
                            Name
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" placeholder="Enter level name here." id="levelName"
                            required="" name="name">
                        <div class="invalid-feedback">Please enter level name.</div>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary">Add New Level</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
