@extends('admin.layouts.master')
@section('page_title', 'Edit Blog')
@push('styles')
@endpush
@section('content')
    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
                <div class="mb-3 mb-md-0">
                    <h1 class="mb-1 h2 fw-bold">Blogs</h1>
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.blogs.index') }}">Blogs</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('admin.blogs.index') }}" class="btn btn-primary">
                        <i class="bi bi-back me-1"></i> Back to Blogs
                    </a>
                </div>
            </div>s
        </div>
    </div>
    <form method="POST" action="{{ route('admin.blogs.update', $blog) }}" enctype="multipart/form-data">
        @method('PUT')
        @include('admin.pages.blogs._form')
    </form>
@endsection
@push('scripts')
    @include('admin.pages.blogs._form-scripts')
@endpush
