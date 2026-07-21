@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@push('name')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
@endpush
@section('content')
    @include('frontend.staff.pages.partials.breadcrumb')
    <div class="card card-body">
        <form class="position-relative" action="{{ route('staff.curriculum.index') }}" method="GET">
            <input type="search" class="form-control product-search ps-5" placeholder="Search Course" name="search"
                value="{{ request()->search ?? '' }}">
            <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
        </form>
    </div>
    <div class="card card-body">
        <div class="table-responsive">
            <table class="table align-middle mb-0 text-nowrap">
                <thead>
                    <tr class="text-muted fw-semibold">
                        <th class="ps-0">Course</th>
                        <th class="ps-0">Instructor</th>
                        <th class="ps-0">Chapters</th>
                        <th class="ps-0">Lessons</th>
                        <th class="text-end ps-0">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($courses as $course)
                        <tr>
                            <td class="ps-0">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ asset($course->thumbnail == '' ? '/default-images/staff/no-course-img.png' : $course->thumbnail) }}"
                                        class="rounded" width="60" height="45" style="object-fit: cover;"
                                        loading="lazy" decoding="async">
                                    <div>
                                        <h6 class="mb-0 fw-semibold">{{ $course->title }}</h6>
                                        @if ($course->khmer_title)
                                            <div class="fs-2 text-muted">{{ $course->khmer_title }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="ps-0">{{ $course->instructor->name ?? '-' }}</td>
                            <td class="ps-0">
                                <span class="badge bg-light-info text-dark rounded-pill">
                                    {{-- {{ $course->chapters_count }} Chapter{{ $course->chapters_count != 1 ? 's' : '' }} --}}
                                    {{ $course->chapters_count }}
                                </span>
                            </td>

                            <td class="ps-0">
                                <span class="badge bg-light-success text-dark rounded-pill">
                                    {{-- {{ $course->lessons_count }} Lesson{{ $course->lessons_count != 1 ? 's' : '' }} --}}
                                    {{ $course->lessons_count }}
                                </span>
                            </td>

                            <td class="text-end ps-0">
                                <button type="button" class="btn btn-sm btn-info btn-open-curriculum"
                                    data-id="{{ $course->id }}" data-title="{{ $course->title }}">
                                    <i class="ti ti-list-details me-1"></i> Manage Curriculum
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                No courses available.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-end mt-4 pt-3 border-top">
            {{ $courses->appends(request()->except('page'))->links('frontend.staff.pages.pagination.custom') }}
        </div>
    </div>
    @include('frontend.staff.pages.course-management.partials.curriculum-modal')
@endsection
@push('scripts')
    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.min.js"></script>
    @include('frontend.staff.pages.course-management.partials.curriculum-scripts')
@endpush
