<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Edit Staff</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body">
        <form action="{{ route('admin.staff.update', $staff->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" value="{{ old('name', $staff->name) }}"
                    placeholder="Name" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" name="email" value="{{ old('email', $staff->email) }}"
                    placeholder="Email" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password"
                    placeholder="Leave blank if not changing password">
            </div>

            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" class="form-control" name="password_confirmation"
                    placeholder="Confirm new password">
            </div>

            <div class="mb-3">
                <label class="form-label">Resume</label>
                <input type="file" class="form-control" name="document">
                <small class="form-text text-muted">
                    Allowed file types: pdf, doc, docx, jpg, png. Max size: 12MB.
                </small>

                @if ($staff->document)
                    <div class="mt-2">
                        Current File:
                        <a href="{{ asset($staff->document) }}" target="_blank">
                            View Resume
                        </a>
                    </div>
                @endif
            </div>

            <button type="submit" class="btn btn-primary">
                Update Staff
            </button>
        </form>
    </div>
</div>
