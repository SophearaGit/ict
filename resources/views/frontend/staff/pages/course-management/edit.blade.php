@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@push('styles')
    <style>
        .select2-container--default .select2-selection--single {
            /* border-radius: var(--bs-border-radius-lg); */
            height: 42px;
            padding: 6px 12px;
            border: 1px solid var(--bs-border-color);
        }

        .select2-container--default .select2-selection--single:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.15);
        }

        .select2-results__group {
            font-weight: 600;
            font-size: 13px;
            padding: 8px 12px;
            color: var(--bs-heading-color);
            background: var(--bs-light);
        }
    </style>
@endpush
@section('content')
    @include('frontend.staff.pages.partials.breadcrumb')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-md-flex align-items-center mb-9">
                        <div>
                            <h5 class="card-title fw-semibold mb-2">
                                Course (Edit)
                            </h5>
                            <p class="card-subtitle text-muted">
                                You can edit course details from this page.
                            </p>
                        </div>
                        <div class="ms-auto mt-4 mt-md-0">
                            <a href="{{ url()->previous() }}" class="btn btn-primary">
                                <i class="ti ti-arrow-back-up me-1"></i> Back
                            </a>
                        </div>
                    </div>
                    <form action="{{ route('staff.courses.update', $course->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @if ($course->thumbnail)
                            <div class="mb-3">
                                <img src="{{ asset($course->thumbnail) }}" style="width:120px; border-radius:10px;"
                                    class="mb-2">
                            </div>
                        @endif
                        <div class="mb-3">
                            <input type="file" class="form-control" name="thumbnail" accept="image/*">
                            <x-input-error :messages="$errors->get('thumbnail')" class="text-danger mt-2" />
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control " placeholder="Course Title" name="title"
                                value="{{ old('title', $course->title) }}">
                            <label><i class="ti ti-book me-2 fs-4 text-info"></i><span
                                    class="border-start border-info ps-3">Title</span></label>
                            <x-input-error :messages="$errors->get('title')" class="text-danger mt-2" />
                        </div>
                        <div class="row">
                            <div class="form-floating mb-3 col-md-4">
                                <select class="form-select" name="instructor_id" id="instructor_id">
                                    <option value="" disabled selected>Select Instructor</option>
                                    @foreach ($instructors as $instructor)
                                        <option value="{{ $instructor->id }}"
                                            {{ old('instructor_id', $course->instructor_id) == $instructor->id ? 'selected' : '' }}>
                                            {{ $instructor->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label style="padding: 1rem 33px; important;"><i
                                        class="ti ti-user-circle me-2 fs-4 text-info"></i><span
                                        class="border-start border-info ps-3">Instructor</span></label>
                                <x-input-error :messages="$errors->get('instructor_id')" class="text-danger mt-2" />
                            </div>
                            <div class="form-floating mb-3 col-md-6">
                                <select class="form-select select2-schedule" name="schedule_id">
                                    <option value="" selected disabled>
                                        Please select schedule
                                    </option>
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
                                <x-input-error :messages="$errors->get('schedule_id')" class="text-danger mt-2" />
                            </div>
                            <div class="form-floating mb-3 col-md-2">
                                <select class="form-select" name="status" id="status">
                                    <option value="" disabled>Select Status</option>
                                    <option value="active" @selected(old('status', $course->status) == 'active')>
                                        Active
                                    </option>
                                    <option value="inactive" @selected(old('status', $course->status) == 'inactive')>
                                        Inactive
                                    </option>
                                </select>
                                <label style="padding: 1rem 33px; important;"><i
                                        class="ti ti-flag me-2 fs-4 text-info"></i><span
                                        class="border-start border-info ps-3">Status</span></label>
                                <x-input-error :messages="$errors->get('status')" class="text-danger mt-2" />
                            </div>
                        </div>
                        <div class="row">
                            {{-- start_date --}}
                            <div class="form-floating mb-3 col-md-2">
                                <input type="date" class="form-control" name="start_date"
                                    value="{{ old('start_date', optional($course->start_date)->format('Y-m-d')) }}">
                                <label><i class="ti ti-calendar me-2 fs-4 text-info"></i><span
                                        class="border-start border-info ps-3">Start Date</span></label>
                                <x-input-error :messages="$errors->get('start_date')" class="text-danger mt-2" />
                            </div>

                            {{-- end_date --}}
                            <div class="form-floating mb-3 col-md-2">
                                <input type="date" class="form-control" name="end_date"
                                    value="{{ old('end_date', optional($course->end_date)->format('Y-m-d')) }}">
                                <label><i class="ti ti-calendar me-2 fs-4 text-info"></i><span
                                        class="border-start border-info ps-3">End
                                        Date</span></label>
                                <x-input-error :messages="$errors->get('end_date')" class="text-danger mt-2" />
                            </div>

                            <div class="form-floating mb-3 col-md-2">
                                <input type="number" class="form-control " placeholder="Course Price" name="price"
                                    step="0.01" value="{{ old('price', $course->price) }}">
                                <label style="padding: 1rem 26px; important;"><i
                                        class="ti ti-currency-dollar me-2 fs-4 text-info"></i><span
                                        class="border-start border-info ps-3">Price</span></label>
                                <x-input-error :messages="$errors->get('price')" class="text-danger mt-2" />
                            </div>

                            <div class="form-floating mb-3 col-md-3">
                                <select class="form-select" name="price_per_session" id="price_per_session">
                                    <option value="" disabled selected>Select Price/Session</option>
                                    @for ($i = 5; $i <= 10; $i += 0.5)
                                        <option value="{{ number_format($i, 2) }}"
                                            {{ old('price_per_session', $course->price_per_session) == number_format($i, 2) ? 'selected' : '' }}>
                                            {{ number_format($i, 2) }} $
                                        </option>
                                    @endfor
                                </select>
                                <label style="padding: 1rem 26px; important;"><i
                                        class="ti ti-currency-dollar me-2 fs-4 text-info"></i><span
                                        class="border-start border-info ps-3">Price/Session</span></label>
                                <x-input-error :messages="$errors->get('price_per_session')" class="text-danger mt-2" />
                            </div>

                            {{-- duration --}}
                            <div class="form-floating mb-3 col-md-3">
                                <input type="number" class="form-control " placeholder="Course Duration"
                                    name="duration" step="0.1" value="{{ old('duration', $course->duration) }}">
                                <label style="padding: 1rem 26px; important;"><i
                                        class="ti ti-clock me-2 fs-4 text-info"></i><span
                                        class="border-start border-info ps-3">Duration (hrs)</span></label>
                                <x-input-error :messages="$errors->get('duration')" class="text-danger mt-2" />
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <textarea class="form-control" placeholder="Course Description" name="description" id="description"
                                style="height: 100px">{!! old('description', $course->description) !!}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="text-danger mt-2" />
                        </div>
                        <div class="d-md-flex align-items-center">
                            <div class="mt-3 mt-md-0 ms-auto">
                                <button type="submit" class="btn btn-info font-medium rounded-pill px-4">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-send me-2 fs-4"></i>
                                        Submit
                                    </div>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        tinymce.init({
            selector: 'textarea',
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        });

        $(document).ready(function() {
            $('.select2-schedule').select2({
                width: '100%',
                placeholder: 'Please select schedule',
                allowClear: true,
                templateResult: formatSchedule,
                templateSelection: formatSelection,
                escapeMarkup: function(markup) {
                    return markup;
                }
            });

            $('.select2-schedule').trigger('change');

            function formatSchedule(option) {

                if (!option.id) return option.text;

                let shift = $(option.element).data('shift');
                let start = $(option.element).data('start');
                let end = $(option.element).data('end');


                let shiftColor = {
                    morning: 'var(--bs-success)',
                    afternoon: 'var(--bs-warning)',
                    evening: 'var(--bs-primary)'
                };

                let color = shiftColor[shift] ?? 'var(--bs-secondary)';

                return `
                    <div class="d-flex justify-content-between align-items-center py-1">
                        <div>
                            <span class="badge me-2"
                                style="background:${color}; border-radius:var(--bs-border-radius-pill);">
                                ${shift.charAt(0).toUpperCase() + shift.slice(1)}
                            </span>
                            <strong class="text-body">
                                ${start} – ${end}
                            </strong>
                        </div>
                    </div>
                `;
            }

            function formatSelection(option) {
                if (!option.id) return option.text;

                let day = $(option.element).parent('optgroup').attr('label') || '';
                let shift = $(option.element).data('shift');
                let start = $(option.element).data('start');
                let end = $(option.element).data('end');

                return `
                    <span style="font-weight:600;">
                        ${day} • ${shift.charAt(0).toUpperCase() + shift.slice(1)} ( ${start} – ${end} )
                    </span>
                `;
            }
        });
    </script>
@endpush
