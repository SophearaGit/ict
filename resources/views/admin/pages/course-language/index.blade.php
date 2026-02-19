@extends('admin.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('content')
    @include('admin.pages.partials.breadcrumb')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <!-- Card -->
            <div class="card mb-4">
                <!-- Card header -->
                <div class="card-header border-bottom-0">
                    <!-- Form -->
                    <form class="d-flex align-items-center" action="{{ route('admin.course-language.index') }}" method="GET">
                        <span class="position-absolute ps-3 search-icon">
                            <i class="fe fe-search"></i>
                        </span>
                        <input type="search" class="form-control ps-6" placeholder="Search Course Language"
                            value="{{ request()->search ?? '' }}" name="search">
                    </form>
                </div>
                <!-- Table -->
                <div class="table-responsive border-0 overflow-y-hidden">
                    <table class="table mb-0 text-nowrap table-centered table-hover table-with-checkbox">
                        <thead class="table-light">
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($course_languages as $course_language)
                                <tr>
                                    <td></td>
                                    <td>
                                        <h5 class="mb-0 text-primary-hover">
                                            {{ $course_language->name }}
                                        </h5>
                                    </td>
                                    <td>
                                        {{ $course_language->slug }}
                                    </td>
                                    <td>
                                        <span class="dropdown dropstart">
                                            <a class="btn-icon btn btn-ghost btn-sm rounded-circle" href="#"
                                                role="button" id="courseDropdown3" data-bs-toggle="dropdown"
                                                data-bs-offset="-20,20" aria-expanded="false">
                                                <i class="fe fe-more-vertical"></i>
                                            </a>
                                            <span class="dropdown-menu" aria-labelledby="courseDropdown3">
                                                <span class="dropdown-header">Action</span>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#editCatgory" data-id="{{ $course_language->id }}"
                                                    data-name="{{ $course_language->name }}">
                                                    <i
                                                        class="fe fe-edit
                                                        dropdown-item-icon"></i>
                                                    Edit
                                                </a>
                                                <a href="#" class="dropdown-item"
                                                    onclick="event.preventDefault(); $('#delete-form-{{ $course_language->id }}').submit();">
                                                    <i class="fe fe-trash dropdown-item-icon"></i>
                                                    Delete
                                                </a>

                                                <form
                                                    action="{{ route('admin.course-language.destroy', $course_language->id) }}"
                                                    method="POST" id="delete-form-{{ $course_language->id }}"
                                                    class="d-none">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>

                                            </span>
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No course languages found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('admin.pages.course-language.modals.create')
    @include('admin.pages.course-language.modals.edit')
@endsection
