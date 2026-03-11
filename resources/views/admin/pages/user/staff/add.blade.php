<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="addStaffModalLabel">Add New Staff</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <form action="{{ route('admin.staff.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password"
                    required>
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password <span
                        class="text-danger">*</span></label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                    placeholder="Confirm Password" required>
            </div>
            <div class="mb-3">
                <label for="document" class="form-label">Resume <span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="document" name="document" required>
                <small class="form-text text-muted">Allowed file types: pdf, doc, docx, jpg, png. Max size:
                    12MB.</small>
            </div>
            <button type="submit" class="btn btn-primary">Add Staff</button>
        </form>
    </div>
</div>
