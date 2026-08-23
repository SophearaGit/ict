@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Add Project')
@section('content')
    @include('frontend.staff.pages.partials.breadcrumb')

    <div class="card card-body">
        <form method="POST" action="{{ route('staff.projects.store') }}" enctype="multipart/form-data">
            @csrf
            @include('frontend.staff.pages.projects.partials.form')
        </form>
    </div>
@endsection
