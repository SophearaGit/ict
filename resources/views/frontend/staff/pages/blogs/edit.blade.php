@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Edit Blog')
@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush
@section('content')
    @include('frontend.staff.pages.partials.breadcrumb')

    <div class="blog-form-topbar">
        <a href="{{ route('staff.blogs.index') }}" class="blog-form-back">
            <i class="ti ti-arrow-left"></i> Back to Blogs
        </a>
        <div class="d-flex align-items-center gap-3 flex-wrap">
            <div class="blog-form-heading">
                <h1>Edit Blog Post</h1>
                <p>{{ $blog->title }}</p>
            </div>
            <button type="submit" form="blog-form" class="blog-btn-save">
                <i class="ti ti-check"></i> Update Blog
            </button>
        </div>
    </div>

    <form id="blog-form" method="POST" action="{{ route('staff.blogs.update', $blog) }}" enctype="multipart/form-data">
        @method('PUT')
        @include('frontend.staff.pages.blogs._form')
    </form>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    @include('frontend.staff.pages.blogs._form-scripts')
@endpush
