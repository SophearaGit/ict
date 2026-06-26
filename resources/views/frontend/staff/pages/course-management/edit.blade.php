@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Edit Course')
@push('styles')
    {{-- Flatpickr --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        /* ── CSS tokens ─────────────────────────────────── */
        :root {
            --form-radius: 10px;
            --section-gap: 2rem;
            --accent: var(--bs-info);
            --accent-soft: rgba(var(--bs-info-rgb), .08);
            --border-subtle: var(--bs-border-color);
            --label-color: var(--bs-secondary-color);
        }

        /* ── Card shell ─────────────────────────────────── */
        .course-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 2px 16px rgba(0, 0, 0, .06);
        }

        /* ── Section labels ─────────────────────────────── */
        .section-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--label-color);
            margin-bottom: 1.1rem;
        }

        .section-label .icon-wrap {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: var(--accent-soft);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
            font-size: 15px;
            flex-shrink: 0;
        }

        .section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border-subtle);
            margin-left: 4px;
        }

        /* ── Form sections ──────────────────────────────── */
        .form-section {
            padding: 1.5rem;
            border-radius: var(--form-radius);
            background: var(--bs-body-bg);
            border: 1px solid var(--border-subtle);
            margin-bottom: var(--section-gap);
            transition: border-color .2s;
        }

        .form-section:focus-within {
            border-color: rgba(var(--bs-info-rgb), .4);
        }

        /* ── Floating label tweaks ──────────────────────── */
        .form-floating>.form-control,
        .form-floating>.form-select {
            border-radius: var(--form-radius);
        }

        .form-floating>label {
            color: var(--label-color);
            font-size: 13px;
        }

        /* ── Non-floating labels ─────────────────────────── */
        .field-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--label-color);
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* ── Thumbnail upload zone ──────────────────────── */
        .thumbnail-zone {
            position: relative;
            border: 2px dashed var(--border-subtle);
            border-radius: var(--form-radius);
            padding: 2rem 1.5rem;
            background: var(--bs-light);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: .6rem;
            cursor: pointer;
            transition: border-color .2s, background .2s;
            min-height: 150px;
            text-align: center;
        }

        .thumbnail-zone:hover,
        .thumbnail-zone.drag-over {
            border-color: var(--accent);
            background: var(--accent-soft);
        }

        .thumbnail-zone .tz-icon {
            font-size: 2.4rem;
            color: var(--bs-secondary-color);
            transition: color .2s;
        }

        .thumbnail-zone:hover .tz-icon {
            color: var(--accent);
        }

        .thumbnail-zone .tz-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--bs-body-color);
            margin: 0;
        }

        .thumbnail-zone .tz-sub {
            font-size: 12px;
            color: var(--bs-secondary-color);
            margin: 0;
        }

        .thumbnail-zone input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }

        /* New-file preview (hidden until file picked) */
        #thumbnail-preview {
            display: none;
            width: 100%;
            max-height: 200px;
            object-fit: cover;
            border-radius: calc(var(--form-radius) - 2px);
        }

        /* ── Current thumbnail strip ─────────────────────── */
        .current-thumb {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            background: var(--bs-light);
            border: 1px solid var(--border-subtle);
            border-radius: var(--form-radius);
            margin-bottom: 10px;
        }

        .current-thumb img {
            width: 80px;
            height: 52px;
            object-fit: cover;
            border-radius: calc(var(--form-radius) - 2px);
            flex-shrink: 0;
        }

        .current-thumb .ct-meta {
            font-size: 12px;
            color: var(--bs-secondary-color);
            line-height: 1.5;
        }

        .current-thumb .ct-meta strong {
            font-size: 13px;
            color: var(--bs-body-color);
            display: block;
        }

        /* ── Select2 ────────────────────────────────────── */
        .select2-container--default .select2-selection--single {
            border-radius: var(--form-radius);
            height: 42px;
            padding: 6px 12px;
            border: 1px solid var(--border-subtle);
            transition: border-color .15s, box-shadow .15s;
        }

        .select2-container--default .select2-selection--single:focus,
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: var(--accent);
            box-shadow: 0 0 0 .2rem rgba(var(--bs-info-rgb), .15);
            outline: none;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 30px;
            color: var(--bs-body-color);
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 42px;
        }

        .select2-dropdown {
            border-radius: var(--form-radius);
            border: 1px solid var(--border-subtle);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .1);
            overflow: hidden;
        }

        .select2-results__group {
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .06em;
            padding: 8px 14px;
            color: var(--label-color);
            background: var(--bs-light);
            border-bottom: 1px solid var(--border-subtle);
        }

        .select2-results__option--highlighted {
            background-color: var(--accent-soft) !important;
            color: var(--bs-body-color) !important;
        }

        /* ── Flatpickr overrides ────────────────────────── */
        .flatpickr-input {
            border-radius: var(--form-radius) !important;
        }

        .flatpickr-calendar {
            border-radius: 12px !important;
            box-shadow: 0 8px 32px rgba(0, 0, 0, .12) !important;
            border: 1px solid var(--border-subtle) !important;
        }

        .flatpickr-day.selected,
        .flatpickr-day.selected:hover {
            background: var(--bs-info) !important;
            border-color: var(--bs-info) !important;
        }

        .flatpickr-day.inRange {
            background: var(--accent-soft) !important;
            border-color: transparent !important;
            box-shadow: -5px 0 0 var(--accent-soft), 5px 0 0 var(--accent-soft) !important;
        }

        .flatpickr-months .flatpickr-prev-month:hover svg,
        .flatpickr-months .flatpickr-next-month:hover svg {
            fill: var(--bs-info) !important;
        }

        /* ── Capacity hint ───────────────────────────────── */
        .cap-hint {
            font-size: 11px;
            color: var(--label-color);
            margin-top: 4px;
        }

        /* ── Submit button ──────────────────────────────── */
        .btn-submit {
            min-width: 160px;
            border-radius: 50px;
            font-weight: 600;
            letter-spacing: .02em;
            padding: .6rem 1.8rem;
            transition: transform .15s, box-shadow .15s;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(var(--bs-info-rgb), .35);
        }

        /* ── Char counter ───────────────────────────────── */
        .char-count {
            font-size: 11px;
            color: var(--label-color);
            text-align: right;
            margin-top: 4px;
        }
    </style>
@endpush
@section('content')
    @include('frontend.staff.pages.partials.breadcrumb')
    <div class="row">
        <div class="col-12">
            <div class="card course-card">
                <div class="card-body p-4">
                    {{-- ── Header ── --}}
                    <div class="d-flex align-items-start justify-content-between mb-4 pb-3 border-bottom">
                        <div>
                            <h4 class="fw-bold mb-1">Edit course</h4>
                            <p class="text-muted mb-0" style="font-size:13px">
                                Update the details below. Fields marked <span class="text-danger">*</span> are required.
                            </p>
                        </div>
                        <a href="{{ url()->previous() }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                            <i class="ti ti-arrow-back-up me-1"></i> Back
                        </a>
                    </div>
                    <form action="{{ route('staff.courses.update', $course->id) }}" method="POST"
                        enctype="multipart/form-data" id="courseForm">
                        @csrf
                        @method('PUT')
                        {{-- ══════════════════════════════════════ --}}
                        {{-- 1 · THUMBNAIL                         --}}
                        {{-- ══════════════════════════════════════ --}}
                        <div class="section-label">
                            <span class="icon-wrap"><i class="ti ti-photo"></i></span>
                            Thumbnail
                        </div>
                        <div class="form-section">
                            <div class="row g-3 align-items-start">
                                <div class="col-md-8">
                                    {{-- Current thumbnail strip (only shown if one exists) --}}
                                    @if ($course->thumbnail)
                                        <div class="current-thumb" id="currentThumb">
                                            <img src="{{ asset($course->thumbnail) }}" alt="Current thumbnail">
                                            <div class="ct-meta">
                                                <strong>Current thumbnail</strong>
                                                Upload a new image below to replace it.
                                            </div>
                                        </div>
                                    @endif
                                    <div class="thumbnail-zone" id="thumbnailZone">
                                        <img id="thumbnail-preview" alt="Preview">
                                        <i class="ti ti-cloud-upload tz-icon" id="tzIcon"></i>
                                        <p class="tz-title" id="tzTitle">
                                            {{ $course->thumbnail ? 'Replace thumbnail image' : 'Drop image here or click to browse' }}
                                        </p>
                                        <p class="tz-sub" id="tzSub">PNG, JPG, WEBP · max 3 MB</p>
                                        <input type="file" name="thumbnail" id="thumbnailInput" accept="image/*">
                                    </div>
                                    <x-input-error :messages="$errors->get('thumbnail')" class="text-danger mt-2" />
                                </div>
                                <div class="col-md-4">
                                    <div class="rounded p-3"
                                        style="background:var(--bs-light);border-radius:var(--form-radius)!important;font-size:12px;color:var(--bs-secondary-color);line-height:1.7;">
                                        <p class="fw-semibold text-body mb-2" style="font-size:13px;">
                                            <i class="ti ti-info-circle me-1 text-info"></i> Tips
                                        </p>
                                        <ul class="ps-3 mb-0">
                                            <li>Recommended: <strong>1280 × 720 px</strong></li>
                                            <li>16:9 aspect ratio works best</li>
                                            <li>Keep file size under 3 MB</li>
                                            <li>Use a clear, high-contrast image</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- ══════════════════════════════════════ --}}
                        {{-- 2 · BASIC INFO                        --}}
                        {{-- ══════════════════════════════════════ --}}
                        <div class="section-label">
                            <span class="icon-wrap"><i class="ti ti-info-circle"></i></span>
                            Basic info
                        </div>
                        <div class="form-section">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" placeholder="Course Title" name="title"
                                            id="title" value="{{ old('title', $course->title) }}" required>
                                        <label for="title">
                                            <i class="ti ti-book me-2 text-info"></i>
                                            Title (English) <span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <x-input-error :messages="$errors->get('title')" class="text-danger mt-1" />
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" placeholder="ឈ្មោះវគ្គសិក្សា"
                                            name="khmer_title" id="khmer_title"
                                            value="{{ old('khmer_title', $course->khmer_title) }}">
                                        <label for="khmer_title">
                                            <i class="ti ti-book me-2 text-info"></i>
                                            ឈ្មោះ (ខ្មែរ)
                                        </label>
                                    </div>
                                    <x-input-error :messages="$errors->get('khmer_title')" class="text-danger mt-1" />
                                </div>
                            </div>
                        </div>
                        {{-- ══════════════════════════════════════ --}}
                        {{-- 3 · ASSIGNMENT                        --}}
                        {{-- ══════════════════════════════════════ --}}
                        <div class="section-label">
                            <span class="icon-wrap"><i class="ti ti-users"></i></span>
                            Assignment
                        </div>
                        <div class="form-section">
                            <div class="row g-3">
                                {{-- Instructor --}}
                                <div class="col-md-3">
                                    <label class="field-label">
                                        <i class="ti ti-user-circle text-info"></i>
                                        Instructor <span class="text-danger">*</span>
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
                                {{-- Category --}}
                                <div class="col-md-3">
                                    <label class="field-label">
                                        <i class="ti ti-category text-info"></i>
                                        Category
                                    </label>
                                    <select class="form-select select2-category" name="category_id" id="category_id">
                                        <option value="" disabled>Select category…</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                data-icon="{{ $category->icon ?? 'ti ti-category' }}"
                                                {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('category_id')" class="text-danger mt-1" />
                                </div>
                                {{-- Schedule --}}
                                <div class="col-md-4">
                                    <label class="field-label">
                                        <i class="ti ti-calendar-time text-info"></i>
                                        Schedule <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select select2-schedule" name="schedule_id">
                                        <option value="" disabled>Select a schedule…</option>
                                        @foreach ($schedules as $studyDay => $daySchedules)
                                            <optgroup label="{{ Str::title(str_replace('-', ' • ', $studyDay)) }}">
                                                @foreach ($daySchedules as $schedule)
                                                    <option value="{{ $schedule->id }}"
                                                        data-shift="{{ strtolower($schedule->shift) }}"
                                                        data-start="{{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i') }}"
                                                        data-end="{{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}"
                                                        @selected(old('schedule_id', $course->schedule_id) == $schedule->id)>
                                                        {{ ucfirst($schedule->shift) }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('schedule_id')" class="text-danger mt-1" />
                                </div>
                                {{-- Status --}}
                                <div class="col-md-2">
                                    <label class="field-label">
                                        <i class="ti ti-flag text-info"></i>
                                        Status <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" name="status" id="status"
                                        style="border-radius:var(--form-radius);height:42px;">
                                        <option value="" disabled>Select…</option>
                                        <option value="active" @selected(old('status', $course->status) == 'active')>Active</option>
                                        <option value="inactive" @selected(old('status', $course->status) == 'inactive')>Inactive</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('status')" class="text-danger mt-1" />
                                </div>
                            </div>
                        </div>
                        {{-- ══════════════════════════════════════ --}}
                        {{-- 4 · DATES & PRICING                   --}}
                        {{-- ══════════════════════════════════════ --}}
                        <div class="section-label">
                            <span class="icon-wrap"><i class="ti ti-calendar-dollar"></i></span>
                            Dates &amp; Pricing
                        </div>
                        <div class="form-section">
                            {{-- Row 1: Start date · End date · Price · Price/session --}}
                            <div class="row g-3 mb-3">
                                {{-- Start date --}}
                                <div class="col-md-3">
                                    <label class="field-label">
                                        <i class="ti ti-calendar-event text-info"></i>
                                        Start date
                                    </label>
                                    <input type="text" class="form-control" id="start_date" name="start_date"
                                        placeholder="Pick a date…"
                                        value="{{ old('start_date', optional($course->start_date)->format('Y-m-d')) }}"
                                        autocomplete="off">
                                    <x-input-error :messages="$errors->get('start_date')" class="text-danger mt-1" />
                                </div>
                                {{-- End date --}}
                                <div class="col-md-3">
                                    <label class="field-label">
                                        <i class="ti ti-calendar-due text-info"></i>
                                        End date
                                    </label>
                                    <input type="text" class="form-control" id="end_date" name="end_date"
                                        placeholder="Pick a date…"
                                        value="{{ old('end_date', optional($course->end_date)->format('Y-m-d')) }}"
                                        autocomplete="off">
                                    <x-input-error :messages="$errors->get('end_date')" class="text-danger mt-1" />
                                </div>
                                {{-- Price --}}
                                <div class="col-md-3">
                                    <div class="form-floating">
                                        <input type="number" class="form-control" placeholder="0.00" name="price"
                                            id="price" step="0.01" min="0"
                                            value="{{ old('price', $course->price) }}" required>
                                        <label for="price">
                                            <i class="ti ti-currency-dollar me-1 text-info"></i>
                                            Total price ($) <span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <x-input-error :messages="$errors->get('price')" class="text-danger mt-1" />
                                </div>
                                {{-- Price / session --}}
                                <div class="col-md-3">
                                    <div class="form-floating">
                                        <select class="form-select" name="price_per_session" id="price_per_session">
                                            <option value="" disabled>Select…</option>
                                            <option value="0.00" @selected(old('price_per_session', $course->price_per_session) == '0.00')>
                                                Free (0.00 $)
                                            </option>
                                            @for ($i = 5; $i <= 10; $i += 0.5)
                                                <option value="{{ number_format($i, 2) }}" @selected(old('price_per_session', $course->price_per_session) == number_format($i, 2))>
                                                    {{ number_format($i, 2) }} $
                                                </option>
                                            @endfor
                                        </select>
                                        <label for="price_per_session">
                                            <i class="ti ti-receipt me-1 text-info"></i>
                                            Price / session
                                        </label>
                                    </div>
                                    <x-input-error :messages="$errors->get('price_per_session')" class="text-danger mt-1" />
                                </div>
                            </div>
                            {{-- Row 2: Duration · Capacity --}}
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="form-floating">
                                        <input type="number" class="form-control" placeholder="0" name="duration"
                                            id="duration" step="0.5" min="0"
                                            value="{{ old('duration', $course->duration) }}">
                                        <label for="duration">
                                            <i class="ti ti-clock me-1 text-info"></i>
                                            Duration (hrs)
                                        </label>
                                    </div>
                                    <x-input-error :messages="$errors->get('duration')" class="text-danger mt-1" />
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating">
                                        <input type="number" class="form-control" placeholder="0" name="capacity"
                                            id="capacity" min="1" step="1"
                                            value="{{ old('capacity', $course->capacity) }}">
                                        <label for="capacity">
                                            <i class="ti ti-users me-1 text-info"></i>
                                            Capacity (seats)
                                        </label>
                                    </div>
                                    <p class="cap-hint"><i class="ti ti-info-circle me-1"></i>Max students allowed to
                                        enrol</p>
                                    <x-input-error :messages="$errors->get('capacity')" class="text-danger mt-1" />
                                </div>
                            </div>
                        </div>
                        {{-- ══════════════════════════════════════ --}}
                        {{-- 5 · DESCRIPTION                       --}}
                        {{-- ══════════════════════════════════════ --}}
                        <div class="section-label">
                            <span class="icon-wrap"><i class="ti ti-align-left"></i></span>
                            Description
                        </div>
                        <div class="form-section">
                            <textarea class="form-control" name="description" id="description"
                                placeholder="Write a clear, concise course description…"
                                style="min-height:160px;border-radius:var(--form-radius);resize:vertical;">{!! old('description', $course->description) !!}</textarea>
                            <div class="char-count"><span id="charCount">0</span> characters</div>
                            <x-input-error :messages="$errors->get('description')" class="text-danger mt-1" />
                        </div>
                        {{-- ── Submit bar ── --}}
                        <div class="d-flex justify-content-end align-items-center gap-3 pt-2">
                            <a href="{{ url()->previous() }}" class="btn btn-link text-muted px-3">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-info btn-submit">
                                <i class="ti ti-device-floppy me-2"></i> Save changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    {{-- Flatpickr --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        $(document).ready(function() {
            /* ── Flatpickr: linked date range (pre-filled) ─────── */
            const startPicker = flatpickr('#start_date', {
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'M j, Y',
                allowInput: false,
                disableMobile: true,
                defaultDate: $('#start_date').val() || null,
                onChange(dates) {
                    if (dates[0]) endPicker.set('minDate', dates[0]);
                },
            });
            const endPicker = flatpickr('#end_date', {
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'M j, Y',
                allowInput: false,
                disableMobile: true,
                defaultDate: $('#end_date').val() || null,
                onChange(dates) {
                    if (dates[0]) startPicker.set('maxDate', dates[0]);
                },
            });
            /* ── Thumbnail preview (new file picked) ───────────── */
            $('#thumbnailInput').on('change', function() {
                const file = this.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = e => {
                    $('#thumbnail-preview').attr('src', e.target.result).show();
                    $('#tzIcon, #tzTitle, #tzSub').hide();
                    $('#currentThumb').hide(); // hide old strip once new one is chosen
                };
                reader.readAsDataURL(file);
            });
            /* Drag visual feedback */
            const zone = document.getElementById('thumbnailZone');
            zone.addEventListener('dragover', e => {
                e.preventDefault();
                zone.classList.add('drag-over');
            });
            zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
            zone.addEventListener('drop', e => {
                e.preventDefault();
                zone.classList.remove('drag-over');
            });
            /* ── Instructor Select2 ─────────────────────────────── */
            $('.select2-instructor').select2({
                width: '100%',
                placeholder: 'Search instructor…',
                allowClear: true,
                templateResult: formatInstructor,
                templateSelection: formatInstructorSelection,
                escapeMarkup: m => m,
            });

            function formatInstructor(opt) {
                if (!opt.id) return opt.text;
                const img = $(opt.element).data('image');
                return `<div class="d-flex align-items-center gap-2 py-1">
                        <img src="${img}" class="rounded-circle" style="width:28px;height:28px;object-fit:cover;">
                        <span>${opt.text}</span>
                    </div>`;
            }

            function formatInstructorSelection(opt) {
                if (!opt.id) return opt.text;
                const img = $(opt.element).data('image');
                return `<div class="d-flex align-items-center gap-2">
                        <img src="${img}" class="rounded-circle" style="width:20px;height:20px;object-fit:cover;">
                        <strong>${opt.text}</strong>
                    </div>`;
            }
            /* ── Category Select2 ──────────────────────────────── */
            $('.select2-category').select2({
                width: '100%',
                placeholder: 'Select category…',
                allowClear: true,
                templateResult: formatCategory,
                templateSelection: formatCategorySelection,
                escapeMarkup: m => m,
            });

            function formatCategory(opt) {
                if (!opt.id) return opt.text;
                const icon = $(opt.element).data('icon') || 'ti ti-category';
                return `<div class="d-flex align-items-center gap-2 py-1">
                        <i class="${icon} fs-5 text-info"></i>
                        <span>${opt.text}</span>
                    </div>`;
            }

            function formatCategorySelection(opt) {
                if (!opt.id) return opt.text;
                const icon = $(opt.element).data('icon') || 'ti ti-category';
                return `<div class="d-flex align-items-center gap-2">
                        <i class="${icon} fs-5 text-info"></i>
                        <strong>${opt.text}</strong>
                    </div>`;
            }
            /* ── Schedule Select2 ──────────────────────────────── */
            const shiftColor = {
                morning: 'var(--bs-success)',
                afternoon: 'var(--bs-warning)',
                evening: 'var(--bs-primary)',
            };
            $('.select2-schedule').select2({
                width: '100%',
                placeholder: 'Select a schedule…',
                allowClear: true,
                templateResult: formatSchedule,
                templateSelection: formatScheduleSelection,
                escapeMarkup: m => m,
            });

            function formatSchedule(opt) {
                if (!opt.id) return opt.text;
                const shift = $(opt.element).data('shift');
                const start = $(opt.element).data('start');
                const end = $(opt.element).data('end');
                const color = shiftColor[shift] ?? 'var(--bs-secondary)';
                const label = shift.charAt(0).toUpperCase() + shift.slice(1);
                return `<div class="d-flex align-items-center gap-2 py-1">
                        <span class="badge rounded-pill" style="background:${color};font-size:11px;">${label}</span>
                        <span class="text-body">${start} – ${end}</span>
                    </div>`;
            }

            function formatScheduleSelection(opt) {
                if (!opt.id) return opt.text;
                const day = $(opt.element).parent('optgroup').attr('label') || '';
                const shift = $(opt.element).data('shift');
                const start = $(opt.element).data('start');
                const end = $(opt.element).data('end');
                const label = shift.charAt(0).toUpperCase() + shift.slice(1);
                return `<strong>${day}</strong> &nbsp;·&nbsp; ${label} &nbsp; ${start} – ${end}`;
            }
            /* ── Description char counter ──────────────────────── */
            $('#description').on('input', function() {
                $('#charCount').text($(this).val().length);
            });
            /* ── TinyMCE ───────────────────────────────────────── */
            tinymce.init({
                selector: '#description',
                plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                setup(editor) {
                    editor.on('init', () => {
                        const len = editor.getContent({
                            format: 'text'
                        }).length;
                        $('#charCount').text(len);
                    });
                    editor.on('input keyup', () => {
                        const len = editor.getContent({
                            format: 'text'
                        }).length;
                        $('#charCount').text(len);
                    });
                },
            });
        });
    </script>
@endpush
