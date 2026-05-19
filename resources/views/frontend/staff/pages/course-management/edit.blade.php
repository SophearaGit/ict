@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@push('styles')
    <style>
        /* ── Select2 base ── */
        .select2-container--default .select2-selection--single {
            border-radius: var(--bs-border-radius-lg);
            height: 42px;
            padding: 6px 12px;
            border: 1px solid var(--bs-border-color);
        }

        .select2-container--default .select2-selection--single:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.15);
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 30px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 42px;
        }

        /* ── Optgroup labels ── */
        .select2-results__group {
            font-weight: 600;
            font-size: 13px;
            padding: 8px 12px;
            color: var(--bs-heading-color);
            background: var(--bs-light);
        }

        /* ── Section dividers ── */
        .form-section-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--bs-secondary-color);
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 1rem;
        }

        .form-section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--bs-border-color);
        }

        /* ── Thumbnail zone ── */
        .thumbnail-zone {
            border: 2px dashed var(--bs-border-color);
            border-radius: var(--bs-border-radius-lg);
            padding: 1.25rem 1rem;
            background: var(--bs-light);
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: border-color 0.2s;
        }

        .thumbnail-zone:hover {
            border-color: var(--bs-info);
        }

        .thumbnail-zone .upload-icon {
            font-size: 2rem;
            color: var(--bs-secondary-color);
            flex-shrink: 0;
        }

        .thumbnail-zone .upload-text {
            font-size: 13px;
            color: var(--bs-secondary-color);
            margin: 0;
        }

        .thumbnail-zone input[type="file"] {
            border: none;
            background: transparent;
            padding: 0;
            font-size: 13px;
        }

        /* ── Current thumbnail preview ── */
        .thumbnail-preview {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            background: var(--bs-light);
            border: 0.5px solid var(--bs-border-color);
            border-radius: var(--bs-border-radius-lg);
            margin-bottom: 10px;
        }

        .thumbnail-preview img {
            width: 72px;
            height: 52px;
            object-fit: cover;
            border-radius: var(--bs-border-radius);
        }

        .thumbnail-preview .preview-meta {
            font-size: 12px;
            color: var(--bs-secondary-color);
        }

        .thumbnail-preview .preview-meta strong {
            font-size: 13px;
            color: var(--bs-body-color);
            display: block;
        }
    </style>
@endpush
@section('content')
    @include('frontend.staff.pages.partials.breadcrumb')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    {{-- ── Header ── --}}
                    <div class="d-md-flex align-items-center mb-4">
                        <div>
                            <h5 class="card-title fw-semibold mb-1">Course (Edit)</h5>
                            <p class="card-subtitle text-muted">You can edit course details from this page.</p>
                        </div>
                        <div class="ms-auto mt-3 mt-md-0">
                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                                <i class="ti ti-arrow-back-up me-1"></i> Back
                            </a>
                        </div>
                    </div>
                    <form action="{{ route('staff.courses.update', $course->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        {{-- ═══════════════════════════════════════ --}}
                        {{-- SECTION 1 · Thumbnail                  --}}
                        {{-- ═══════════════════════════════════════ --}}
                        <div class="form-section-label">
                            <i class="ti ti-photo fs-5"></i> Thumbnail
                        </div>
                        @if ($course->thumbnail)
                            <div class="thumbnail-preview">
                                <img src="{{ asset($course->thumbnail) }}" alt="Current thumbnail">
                                <div class="preview-meta">
                                    <strong>Current thumbnail</strong>
                                    Upload a new image below to replace it.
                                </div>
                            </div>
                        @endif
                        <div class="thumbnail-zone mb-4">
                            <i class="ti ti-cloud-upload upload-icon"></i>
                            <div>
                                <p class="upload-text mb-1">
                                    {{ $course->thumbnail ? 'Replace thumbnail image' : 'Upload a course thumbnail image' }}
                                </p>
                                <input type="file" name="thumbnail" accept="image/*">
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('thumbnail')" class="text-danger mb-3" />
                        {{-- ═══════════════════════════════════════ --}}
                        {{-- SECTION 2 · Basic Info                 --}}
                        {{-- ═══════════════════════════════════════ --}}
                        <div class="form-section-label">
                            <i class="ti ti-info-circle fs-5"></i> Basic info
                        </div>
                        <div class="row g-3 mb-4">
                            {{-- Title (English) --}}
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" placeholder="Course Title" name="title"
                                        value="{{ old('title', $course->title) }}">
                                    <label>
                                        <i class="ti ti-book me-2 fs-4 text-info"></i>
                                        <span class="border-start border-info ps-3">Title (English)</span>
                                    </label>
                                </div>
                                <x-input-error :messages="$errors->get('title')" class="text-danger mt-1" />
                            </div>
                            {{-- Title (Khmer) --}}
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" placeholder="ឈ្មោះវគ្គសិក្សា"
                                        name="khmer_title" value="{{ old('khmer_title', $course->khmer_title) }}">
                                    <label>
                                        <i class="ti ti-book me-2 fs-4 text-info"></i>
                                        <span class="border-start border-info ps-3">ឈ្មោះ (ខ្មែរ)</span>
                                    </label>
                                </div>
                                <x-input-error :messages="$errors->get('khmer_title')" class="text-danger mt-1" />
                            </div>
                        </div>
                        {{-- ═══════════════════════════════════════ --}}
                        {{-- SECTION 3 · Assignment                 --}}
                        {{-- ═══════════════════════════════════════ --}}
                        <div class="form-section-label">
                            <i class="ti ti-users fs-5"></i> Assignment
                        </div>
                        <div class="row g-3 mb-4">
                            {{-- Instructor --}}
                            <div class="col-md-4">
                                <label class="form-label text-muted small mb-1">
                                    <i class="ti ti-user-circle me-1"></i> Instructor
                                </label>
                                <select class="form-select select2-instructor" name="instructor_id" id="instructor_id">
                                    <option value="" disabled>Search instructor…</option>
                                    @foreach ($instructors as $instructor)
                                        <option value="{{ $instructor->id }}"
                                            data-image="{{ $instructor->image == 'no-img.jpg' ? asset('/default-images/user/both.jpg') : asset($instructor->image) }}"
                                            {{ old('instructor_id', $course->instructor_id) == $instructor->id ? 'selected' : '' }}>
                                            {{ $instructor->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('instructor_id')" class="text-danger mt-1" />
                            </div>
                            {{-- Schedule --}}
                            <div class="col-md-5">
                                <label class="form-label text-muted small mb-1">
                                    <i class="ti ti-calendar-time me-1"></i> Schedule
                                </label>
                                <select class="form-select select2-schedule" name="schedule_id">
                                    <option value="" disabled>Please select schedule</option>
                                    @foreach ($schedules as $studyDay => $daySchedules)
                                        <optgroup label="{{ Str::title(str_replace('-', ' • ', $studyDay)) }}">
                                            @foreach ($daySchedules as $schedule)
                                                <option value="{{ $schedule->id }}" @selected(old('schedule_id', $course->schedule_id) == $schedule->id)
                                                    data-shift="{{ strtolower($schedule->shift) }}"
                                                    data-start="{{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i') }}"
                                                    data-end="{{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}">
                                                    {{ ucfirst($schedule->shift) }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('schedule_id')" class="text-danger mt-1" />
                            </div>
                            {{-- Status --}}
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <select class="form-select" name="status" id="status">
                                        <option value="" disabled>Select status</option>
                                        <option value="active" @selected(old('status', $course->status) == 'active')>Active</option>
                                        <option value="inactive" @selected(old('status', $course->status) == 'inactive')>Inactive</option>
                                    </select>
                                    <label>
                                        <i class="ti ti-flag me-2 fs-4 text-info"></i>
                                        <span class="border-start border-info ps-3">Status</span>
                                    </label>
                                </div>
                                <x-input-error :messages="$errors->get('status')" class="text-danger mt-1" />
                            </div>
                        </div>
                        {{-- ═══════════════════════════════════════ --}}
                        {{-- SECTION 4 · Dates & Pricing            --}}
                        {{-- ═══════════════════════════════════════ --}}
                        <div class="form-section-label">
                            <i class="ti ti-calendar-dollar fs-5"></i> Dates &amp; pricing
                        </div>
                        <div class="row g-3 mb-4">
                            {{-- Start Date --}}
                            <div class="col-md-2">
                                <div class="form-floating">
                                    <input type="date" class="form-control" placeholder="Start Date" name="start_date"
                                        value="{{ old('start_date', optional($course->start_date)->format('Y-m-d')) }}">
                                    <label>
                                        <i class="ti ti-calendar-start me-2 fs-4 text-info"></i>
                                        <span class="border-start border-info ps-3">Start date</span>
                                    </label>
                                </div>
                                <x-input-error :messages="$errors->get('start_date')" class="text-danger mt-1" />
                            </div>
                            {{-- End Date --}}
                            <div class="col-md-2">
                                <div class="form-floating">
                                    <input type="date" class="form-control" placeholder="End Date" name="end_date"
                                        value="{{ old('end_date', optional($course->end_date)->format('Y-m-d')) }}">
                                    <label>
                                        <i class="ti ti-calendar-end me-2 fs-4 text-info"></i>
                                        <span class="border-start border-info ps-3">End date</span>
                                    </label>
                                </div>
                                <x-input-error :messages="$errors->get('end_date')" class="text-danger mt-1" />
                            </div>
                            {{-- Price --}}
                            <div class="col-md-2">
                                <div class="form-floating">
                                    <input type="number" class="form-control" placeholder="Price" name="price"
                                        step="0.01" value="{{ old('price', $course->price) }}">
                                    <label>
                                        <i class="ti ti-currency-dollar me-2 fs-4 text-info"></i>
                                        <span class="border-start border-info ps-3">Price ($)</span>
                                    </label>
                                </div>
                                <x-input-error :messages="$errors->get('price')" class="text-danger mt-1" />
                            </div>
                            {{-- Price per Session --}}
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <select class="form-select" name="price_per_session" id="price_per_session">
                                        <option value="" disabled>Select…</option>
                                        <option value="0.00"
                                            {{ old('price_per_session', $course->price_per_session) == '0.00' ? 'selected' : '' }}>
                                            Free (0.00 $)
                                        </option>
                                        @for ($i = 5; $i <= 10; $i += 0.5)
                                            <option value="{{ number_format($i, 2) }}"
                                                {{ old('price_per_session', $course->price_per_session) == number_format($i, 2) ? 'selected' : '' }}>
                                                {{ number_format($i, 2) }} $
                                            </option>
                                        @endfor
                                    </select>
                                    <label>
                                        <i class="ti ti-currency-dollar me-2 fs-4 text-info"></i>
                                        <span class="border-start border-info ps-3">Price / session</span>
                                    </label>
                                </div>
                                <x-input-error :messages="$errors->get('price_per_session')" class="text-danger mt-1" />
                            </div>
                            {{-- Duration --}}
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="number" class="form-control" placeholder="Duration" name="duration"
                                        step="0.1" value="{{ old('duration', $course->duration) }}">
                                    <label>
                                        <i class="ti ti-clock me-2 fs-4 text-info"></i>
                                        <span class="border-start border-info ps-3">Duration (hrs)</span>
                                    </label>
                                </div>
                                <x-input-error :messages="$errors->get('duration')" class="text-danger mt-1" />
                            </div>
                        </div>
                        {{-- ═══════════════════════════════════════ --}}
                        {{-- SECTION 5 · Description                --}}
                        {{-- ═══════════════════════════════════════ --}}
                        <div class="form-section-label">
                            <i class="ti ti-align-left fs-5"></i> Description
                        </div>
                        <div class="mb-4">
                            <textarea class="form-control" placeholder="Course description…" name="description" id="description"
                                style="height: 140px">{!! old('description', $course->description) !!}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="text-danger mt-1" />
                        </div>
                        {{-- ── Submit ── --}}
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-info rounded-pill px-4">
                                <i class="ti ti-send me-2 fs-5"></i> Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            const shiftColor = {
                morning: 'var(--bs-success)',
                afternoon: 'var(--bs-warning)',
                evening: 'var(--bs-primary)',
            };
            // ── Instructor Select2 ──────────────────────────────────────
            $('.select2-instructor').select2({
                width: '100%',
                placeholder: 'Search instructor…',
                allowClear: true,
                templateResult: formatInstructor,
                templateSelection: formatInstructorSelection,
                escapeMarkup: markup => markup,
            });
            $('.select2-instructor').trigger('change');

            function formatInstructor(option) {
                if (!option.id) return option.text;
                const img = $(option.element).data('image');
                return `
                    <div class="d-flex align-items-center gap-2 py-1">
                        <img src="${img}" class="rounded-circle" style="width:28px;height:28px;object-fit:cover;">
                        <span>${option.text}</span>
                    </div>`;
            }

            function formatInstructorSelection(option) {
                if (!option.id) return option.text;
                const img = $(option.element).data('image');
                return `
                    <div class="d-flex align-items-center gap-2">
                        <img src="${img}" class="rounded-circle" style="width:22px;height:22px;object-fit:cover;">
                        <strong>${option.text}</strong>
                    </div>`;
            }
            // ── Schedule Select2 ────────────────────────────────────────
            $('.select2-schedule').select2({
                width: '100%',
                placeholder: 'Please select schedule',
                allowClear: true,
                templateResult: formatSchedule,
                templateSelection: formatSelection,
                escapeMarkup: markup => markup,
            });
            $('.select2-schedule').trigger('change');

            function formatSchedule(option) {
                if (!option.id) return option.text;
                const shift = $(option.element).data('shift');
                const start = $(option.element).data('start');
                const end = $(option.element).data('end');
                const color = shiftColor[shift] ?? 'var(--bs-secondary)';
                const label = shift.charAt(0).toUpperCase() + shift.slice(1);
                return `
                    <div class="d-flex align-items-center gap-2 py-1">
                        <span class="badge" style="background:${color};border-radius:var(--bs-border-radius-pill);">
                            ${label}
                        </span>
                        <strong class="text-body">${start} – ${end}</strong>
                    </div>`;
            }

            function formatSelection(option) {
                if (!option.id) return option.text;
                const day = $(option.element).parent('optgroup').attr('label') || '';
                const shift = $(option.element).data('shift');
                const start = $(option.element).data('start');
                const end = $(option.element).data('end');
                const label = shift.charAt(0).toUpperCase() + shift.slice(1);
                return `<span style="font-weight:600;">${day} • ${label} ( ${start} – ${end} )</span>`;
            }
        });
        // ── TinyMCE ─────────────────────────────────────────────────────
        tinymce.init({
            selector: '#description',
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        });
    </script>
@endpush
