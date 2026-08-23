@php
    $isEdit = isset($project);
@endphp

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
    <style>
        .image-drop {
            border: 1px dashed var(--bs-border-color, #dee2e6);
            border-radius: .5rem;
            padding: .75rem;
            text-align: center;
            cursor: pointer;
            transition: border-color .15s ease;
        }
        .image-drop:hover {
            border-color: var(--bs-info, #0d6efd);
        }
        .image-drop img {
            max-height: 120px;
            width: 100%;
            object-fit: cover;
            border-radius: .375rem;
        }
        .screenshot-preview-item {
            position: relative;
            width: 100px;
        }
        .screenshot-preview-item img {
            width: 100px;
            height: 70px;
            object-fit: cover;
            border-radius: .375rem;
        }
    </style>
@endpush

<div class="row">
    <div class="col-md-8">
        <div class="mb-3">
            <label class="form-label fw-semibold">Project Title <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                placeholder="e.g. BrandForge — Identity Design System"
                value="{{ old('title', $project->title ?? '') }}">
            @error('title')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label fw-semibold">Category</label>
            <select name="category_id" class="form-select select2 @error('category_id') is-invalid @enderror"
                data-placeholder="Select Category">
                <option></option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ (string) old('category_id', $project->category_id ?? '') === (string) $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Short Excerpt</label>
    <input type="text" name="excerpt" class="form-control @error('excerpt') is-invalid @enderror"
        placeholder="One line shown on the project card"
        value="{{ old('excerpt', $project->excerpt ?? '') }}" maxlength="500">
    @error('excerpt')
        <span class="text-danger small">{{ $message }}</span>
    @enderror
</div>

<div class="row">
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label fw-semibold">Student</label>
            <select name="student_id" class="form-select select2 @error('student_id') is-invalid @enderror"
                data-placeholder="Select Student">
                <option></option>
                @foreach ($students as $student)
                    <option value="{{ $student->id }}"
                        {{ (string) old('student_id', $project->student_id ?? '') === (string) $student->id ? 'selected' : '' }}>
                        {{ $student->name }}
                    </option>
                @endforeach
            </select>
            @error('student_id')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label fw-semibold">Instructor</label>
            <select name="instructor_id" class="form-select select2 @error('instructor_id') is-invalid @enderror"
                data-placeholder="Select Instructor">
                <option></option>
                @foreach ($instructors as $instructor)
                    <option value="{{ $instructor->id }}"
                        {{ (string) old('instructor_id', $project->instructor_id ?? '') === (string) $instructor->id ? 'selected' : '' }}>
                        {{ $instructor->name }}
                    </option>
                @endforeach
            </select>
            @error('instructor_id')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label fw-semibold">Batch</label>
            <input type="text" name="batch_label" class="form-control @error('batch_label') is-invalid @enderror"
                placeholder="e.g. Batch 12" value="{{ old('batch_label', $project->batch_label ?? '') }}">
            @error('batch_label')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

{{-- Thumbnail / Cover image — click to pick, crop, and preview before upload --}}
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label fw-semibold">Thumbnail (card image) <span class="text-muted fw-normal">— 8:5 ratio</span></label>
            <div class="image-drop" data-pick-for="thumbnail-file">
                <img id="thumbnail-preview" src="{{ $isEdit && $project->thumbnail_url ? $project->thumbnail_url : asset('admin/assets/dist/images/backgrounds/img-placeholder.jpg') }}"
                    alt="thumbnail preview">
                <div class="small text-muted mt-2"><i class="ti ti-camera-plus me-1"></i> Click to choose & crop</div>
            </div>
            <input type="file" id="thumbnail-file" name="thumbnail" accept="image/*"
                class="d-none image-crop-input" data-aspect="1.6" data-preview="thumbnail-preview">
            @error('thumbnail')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
            @if ($isEdit && $project->thumbnail_url)
                <small class="text-muted d-block mt-1">Leave as-is to keep the current image.</small>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label fw-semibold">Cover Image (detail page hero) <span class="text-muted fw-normal">— 21:9 ratio</span></label>
            <div class="image-drop" data-pick-for="cover-file">
                <img id="cover-preview" src="{{ $isEdit && $project->cover_image_url ? $project->cover_image_url : asset('admin/assets/dist/images/backgrounds/img-placeholder.jpg') }}"
                    alt="cover preview">
                <div class="small text-muted mt-2"><i class="ti ti-camera-plus me-1"></i> Click to choose & crop</div>
            </div>
            <input type="file" id="cover-file" name="cover_image" accept="image/*"
                class="d-none image-crop-input" data-aspect="2.33" data-preview="cover-preview">
            @error('cover_image')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
            @if ($isEdit && $project->cover_image_url)
                <small class="text-muted d-block mt-1">Leave as-is to keep the current image.</small>
            @endif
        </div>
    </div>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Project Overview</label>
    <textarea name="overview" class="form-control @error('overview') is-invalid @enderror" rows="3"
        placeholder="What is this project about?">{{ old('overview', $project->overview ?? '') }}</textarea>
    @error('overview')
        <span class="text-danger small">{{ $message }}</span>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Problem Statement</label>
    <textarea name="problem_statement" class="form-control @error('problem_statement') is-invalid @enderror"
        rows="2">{{ old('problem_statement', $project->problem_statement ?? '') }}</textarea>
    @error('problem_statement')
        <span class="text-danger small">{{ $message }}</span>
    @enderror
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label fw-semibold">Challenges</label>
            <textarea name="challenges" class="form-control @error('challenges') is-invalid @enderror"
                rows="3">{{ old('challenges', $project->challenges ?? '') }}</textarea>
            @error('challenges')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label fw-semibold">Solutions</label>
            <textarea name="solutions" class="form-control @error('solutions') is-invalid @enderror"
                rows="3">{{ old('solutions', $project->solutions ?? '') }}</textarea>
            @error('solutions')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

{{-- Technologies --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Technologies Used</label>
    <div id="technologies-list">
        @php $technologies = old('technologies', $isEdit ? $project->technologies->pluck('name')->all() : ['']); @endphp
        @forelse ($technologies as $tech)
            <div class="input-group mb-2 repeatable-row">
                <input type="text" name="technologies[]" class="form-control" placeholder="e.g. Figma"
                    value="{{ $tech }}">
                <button type="button" class="btn btn-outline-danger btn-remove-row"><i class="ti ti-x"></i></button>
            </div>
        @empty
        @endforelse
    </div>
    <button type="button" class="btn btn-sm btn-outline-info" id="add-technology">
        <i class="ti ti-plus me-1"></i> Add Technology
    </button>
</div>

{{-- Objectives --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Objectives</label>
    <div id="objectives-list">
        @php $objectives = old('objectives', $isEdit ? $project->objectives->pluck('content')->all() : ['']); @endphp
        @forelse ($objectives as $objective)
            <div class="input-group mb-2 repeatable-row">
                <input type="text" name="objectives[]" class="form-control" placeholder="e.g. Reusable component library"
                    value="{{ $objective }}">
                <button type="button" class="btn btn-outline-danger btn-remove-row"><i class="ti ti-x"></i></button>
            </div>
        @empty
        @endforelse
    </div>
    <button type="button" class="btn btn-sm btn-outline-info" id="add-objective">
        <i class="ti ti-plus me-1"></i> Add Objective
    </button>
</div>

{{-- Development Process Steps --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Development Process</label>
    <div id="process-steps-list">
        @php $steps = old('process_steps', $isEdit ? $project->processSteps->map(fn($s) => ['title' => $s->title, 'description' => $s->description])->all() : [['title' => '', 'description' => '']]); @endphp
        @foreach ($steps as $i => $step)
            <div class="row g-2 mb-2 repeatable-row align-items-start">
                <div class="col-md-3">
                    <input type="text" name="process_steps[{{ $i }}][title]" class="form-control"
                        placeholder="e.g. Research" value="{{ $step['title'] ?? '' }}">
                </div>
                <div class="col-md-8">
                    <input type="text" name="process_steps[{{ $i }}][description]" class="form-control"
                        placeholder="Short description" value="{{ $step['description'] ?? '' }}">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-outline-danger btn-remove-row w-100"><i class="ti ti-x"></i></button>
                </div>
            </div>
        @endforeach
    </div>
    <button type="button" class="btn btn-sm btn-outline-info" id="add-process-step">
        <i class="ti ti-plus me-1"></i> Add Step
    </button>
</div>

{{-- Screenshots --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Screenshots Gallery</label>
    @if ($isEdit && $project->screenshots->count())
        <div class="row g-2 mb-2">
            @foreach ($project->screenshots as $screenshot)
                <div class="col-auto text-center">
                    <img src="{{ $screenshot->image_url }}" alt="screenshot" class="rounded d-block mb-1"
                        width="100" height="70" style="object-fit:cover;">
                    <label class="fs-3 text-danger">
                        <input type="checkbox" name="remove_screenshots[]" value="{{ $screenshot->id }}">
                        Remove
                    </label>
                </div>
            @endforeach
        </div>
    @endif
    <div class="image-drop" data-pick-for="screenshots-file" style="max-width: 220px;">
        <i class="ti ti-photo-plus fs-4"></i>
        <div class="small text-muted mt-1">Click to add screenshots</div>
    </div>
    <input type="file" id="screenshots-file" name="screenshots[]" accept="image/*" multiple class="d-none">
    <div id="screenshots-preview" class="d-flex flex-wrap gap-2 mt-2"></div>
    <small class="text-muted d-block mt-1">New screenshots are added to the gallery — select multiple files at once.</small>
    @error('screenshots')
        <span class="text-danger small d-block">{{ $message }}</span>
    @enderror
</div>

<div class="row">
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label fw-semibold">Live Demo URL</label>
            <input type="url" name="live_demo_url" class="form-control @error('live_demo_url') is-invalid @enderror"
                placeholder="https://" value="{{ old('live_demo_url', $project->live_demo_url ?? '') }}">
            @error('live_demo_url')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label fw-semibold">GitHub URL</label>
            <input type="url" name="github_url" class="form-control @error('github_url') is-invalid @enderror"
                placeholder="https://github.com/..." value="{{ old('github_url', $project->github_url ?? '') }}">
            @error('github_url')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label fw-semibold">Documentation URL</label>
            <input type="url" name="documentation_url" class="form-control @error('documentation_url') is-invalid @enderror"
                placeholder="https://" value="{{ old('documentation_url', $project->documentation_url ?? '') }}">
            @error('documentation_url')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-semibold">Build Duration</label>
            <input type="text" name="build_duration" class="form-control @error('build_duration') is-invalid @enderror"
                placeholder="e.g. 4 month" value="{{ old('build_duration', $project->build_duration ?? '') }}">
            @error('build_duration')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
            <select name="status" class="form-select select2 @error('status') is-invalid @enderror">
                <option value="draft" {{ old('status', $project->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ old('status', $project->status ?? '') === 'published' ? 'selected' : '' }}>Published</option>
            </select>
            @error('status')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-semibold d-block">Featured</label>
            <div class="form-check form-switch mt-2">
                <input class="form-check-input" type="checkbox" role="switch" id="is_featured" name="is_featured"
                    value="1" {{ old('is_featured', $project->is_featured ?? false) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_featured">Show as featured</label>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-semibold">Featured Label</label>
            <input type="text" name="featured_label" class="form-control @error('featured_label') is-invalid @enderror"
                placeholder="e.g. First Place" value="{{ old('featured_label', $project->featured_label ?? '') }}">
            @error('featured_label')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label fw-semibold">Meta Title</label>
            <input type="text" name="meta_title" class="form-control @error('meta_title') is-invalid @enderror"
                value="{{ old('meta_title', $project->meta_title ?? '') }}">
            @error('meta_title')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label fw-semibold">Meta Description</label>
            <input type="text" name="meta_description" class="form-control @error('meta_description') is-invalid @enderror"
                value="{{ old('meta_description', $project->meta_description ?? '') }}">
            @error('meta_description')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('staff.projects.index') }}" class="btn btn-secondary rounded-pill px-4">
        <i class="ti ti-x me-1"></i> Cancel
    </a>
    <button type="submit" class="btn btn-success rounded-pill px-4">
        <i class="ti ti-device-floppy me-1"></i> {{ $isEdit ? 'Save Changes' : 'Create Project' }}
    </button>
</div>

{{-- CROP MODAL — shared by thumbnail & cover image --}}
<div class="modal fade" id="imageCropModal" tabindex="-1" aria-hidden="true" style="display:none;">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h5 class="modal-title"><i class="ti ti-crop me-2"></i> Crop Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div style="max-height: 60vh;">
                    <img id="crop-target" style="max-width: 100%;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success rounded-pill px-4" id="crop-apply-btn">
                    <i class="ti ti-check me-1"></i> Apply Crop
                </button>
                <button type="button" class="btn btn-danger rounded-pill px-4" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i> Cancel
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <script>
        // ─── Select2 (searchable dropdowns) ────────────────────────────────────────
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%',
                allowClear: true,
                placeholder: function() {
                    return $(this).data('placeholder') || 'Select an option';
                }
            });
        });

        // ─── Repeatable rows: Technologies / Objectives (simple single input) ─────
        function addRepeatableInput(containerId, name, placeholder) {
            const container = document.getElementById(containerId);
            const row = document.createElement('div');
            row.className = 'input-group mb-2 repeatable-row';
            row.innerHTML = `
                <input type="text" name="${name}" class="form-control" placeholder="${placeholder}">
                <button type="button" class="btn btn-outline-danger btn-remove-row"><i class="ti ti-x"></i></button>
            `;
            container.appendChild(row);
        }
        document.getElementById('add-technology').addEventListener('click', function() {
            addRepeatableInput('technologies-list', 'technologies[]', 'e.g. Figma');
        });
        document.getElementById('add-objective').addEventListener('click', function() {
            addRepeatableInput('objectives-list', 'objectives[]', 'e.g. Accessible color system');
        });

        // ─── Repeatable rows: Process Steps (title + description) ─────────────────
        let processStepIndex = document.querySelectorAll('#process-steps-list .repeatable-row').length;
        document.getElementById('add-process-step').addEventListener('click', function() {
            const container = document.getElementById('process-steps-list');
            const row = document.createElement('div');
            row.className = 'row g-2 mb-2 repeatable-row align-items-start';
            row.innerHTML = `
                <div class="col-md-3">
                    <input type="text" name="process_steps[${processStepIndex}][title]" class="form-control" placeholder="e.g. Planning">
                </div>
                <div class="col-md-8">
                    <input type="text" name="process_steps[${processStepIndex}][description]" class="form-control" placeholder="Short description">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-outline-danger btn-remove-row w-100"><i class="ti ti-x"></i></button>
                </div>
            `;
            container.appendChild(row);
            processStepIndex++;
        });

        // ─── Remove any repeatable row ──────────────────────────────────────────────
        document.addEventListener('click', function(e) {
            if (e.target.closest('.btn-remove-row')) {
                e.target.closest('.repeatable-row').remove();
            }
        });

        // ─── Image pick trigger (click the drop zone -> opens the real file input) ─
        document.querySelectorAll('[data-pick-for]').forEach(function(box) {
            box.addEventListener('click', function() {
                document.getElementById(this.dataset.pickFor).click();
            });
        });

        // ─── Crop & Preview for thumbnail / cover image ────────────────────────────
        let activeInput = null;
        let activePreview = null;
        let cropperInstance = null;
        const cropModalEl = document.getElementById('imageCropModal');
        const cropModal = new bootstrap.Modal(cropModalEl);
        const cropTarget = document.getElementById('crop-target');

        document.querySelectorAll('.image-crop-input').forEach(function(input) {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;

                activeInput = input;
                activePreview = document.getElementById(input.dataset.preview);

                const reader = new FileReader();
                reader.onload = function(ev) {
                    cropTarget.src = ev.target.result;
                    cropModal.show();
                };
                reader.readAsDataURL(file);
            });
        });

        cropModalEl.addEventListener('shown.bs.modal', function() {
            if (cropperInstance) {
                cropperInstance.destroy();
            }
            cropperInstance = new Cropper(cropTarget, {
                aspectRatio: parseFloat(activeInput.dataset.aspect) || NaN,
                viewMode: 1,
                autoCropArea: 1,
                responsive: true,
            });
        });

        cropModalEl.addEventListener('hidden.bs.modal', function() {
            if (cropperInstance) {
                cropperInstance.destroy();
                cropperInstance = null;
            }
        });

        document.getElementById('crop-apply-btn').addEventListener('click', function() {
            if (!cropperInstance || !activeInput) return;

            cropperInstance.getCroppedCanvas().toBlob(function(blob) {
                const originalName = activeInput.files[0]?.name || 'image.jpg';
                const croppedFile = new File([blob], originalName, { type: blob.type || 'image/jpeg' });

                // Swap the input's FileList with the cropped version so the normal
                // multipart form submission uploads the cropped image.
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(croppedFile);
                activeInput.files = dataTransfer.files;

                activePreview.src = URL.createObjectURL(blob);

                cropModal.hide();
            }, 'image/jpeg', 0.92);
        });

        // ─── Screenshots: live preview strip (no crop — multiple files) ───────────
        const screenshotsInput = document.getElementById('screenshots-file');
        const screenshotsPreview = document.getElementById('screenshots-preview');
        screenshotsInput.addEventListener('change', function() {
            screenshotsPreview.innerHTML = '';
            Array.from(screenshotsInput.files).forEach(function(file) {
                const wrap = document.createElement('div');
                wrap.className = 'screenshot-preview-item';
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                wrap.appendChild(img);
                screenshotsPreview.appendChild(wrap);
            });
        });
    </script>
@endpush
