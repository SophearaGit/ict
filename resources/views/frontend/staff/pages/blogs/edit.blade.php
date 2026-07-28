@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Edit Blog')
@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush
@section('content')
    @include('frontend.staff.pages.partials.breadcrumb')

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('staff.blogs.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i> Back to Blogs
        </a>
    </div>

    <form method="POST" action="{{ route('staff.blogs.update', $blog) }}" enctype="multipart/form-data">
        @method('PUT')
        @include('frontend.staff.pages.blogs._form')
    </form>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    @include('frontend.staff.pages.blogs._form-scripts')
@endpush
