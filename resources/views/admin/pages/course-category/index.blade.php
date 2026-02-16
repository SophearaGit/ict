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
                    <form class="d-flex align-items-center" action="{{ route('admin.course-category.index') }}" method="get">
                        <span class="position-absolute ps-3 search-icon">
                            <i class="fe fe-search"></i>
                        </span>
                        <input type="search" class="form-control ps-6" placeholder="Search Course Category"
                            value="{{ request()->search ?? '' }}" name="search">
                    </form>
                </div>
                <!-- Table -->
                <div class="table-responsive border-0 overflow-y-hidden">
                    <table class="table mb-0 text-nowrap table-centered table-hover table-with-checkbox">
                        <thead class="table-light">
                            <tr>
                                <th>
                                </th>
                                <th>Category</th>
                                <th>Trending</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Updated</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($course_categories as $course_category)
                                <tr class="accordion-toggle collapsed" id="accordion{{ $course_category->id }}"
                                    data-bs-toggle="collapse" data-bs-parent="#accordion{{ $course_category->id }}"
                                    data-bs-target="#collapse{{ $course_category->id }}" aria-expanded="false">
                                    <td>
                                    </td>
                                    <td>
                                        @if ($course_category->subCategories->count() > 0)
                                            <a href="#" class="text-inherit position-relative">
                                                <h5 class="mb-0 text-primary-hover">
                                                    <i
                                                        class="fe fe-chevron-down fs-4 me-2 position-absolute ms-n4 mt-1"></i>
                                                    {{ $course_category->name }}
                                                </h5>
                                            </a>
                                        @else
                                            <h5 class="mb-0 text-primary-hover">
                                                {{ $course_category->name }}
                                            </h5>
                                        @endif
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $course_category->show_at_trending ? 'success' : 'danger' }}">
                                            {{ $course_category->show_at_trending ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $course_category->status ? 'success' : 'danger' }}">
                                            {{ $course_category->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $course_category->created_at->format('d M, Y') }}
                                    </td>
                                    <td>
                                        {{ $course_category->updated_at->format('d M, Y') }}
                                    </td>
                                    <td>
                                        <span class="dropdown dropstart">
                                            <a class="btn-icon btn btn-ghost btn-sm rounded-circle" href="#"
                                                role="button" id="courseDropdown1" data-bs-toggle="dropdown"
                                                data-bs-offset="-20,20" aria-expanded="false">
                                                <i class="fe fe-more-vertical"></i>
                                            </a>
                                            <span class="dropdown-menu" aria-labelledby="courseDropdown1">
                                                <span class="dropdown-header">Action</span>
                                                @if ($course_category->subCategories->count() > 0)
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-bs-target="#editCourseCategoryParent"
                                                        data-id="{{ $course_category->id }}"
                                                        data-name="{{ $course_category->name }}"
                                                        data-icon="{{ $course_category->icon }}"
                                                        data-parent="{{ $course_category->parent_id ? $course_category->parent_id : null }}"
                                                        data-show_at_trending="{{ $course_category->show_at_trending }}"
                                                        data-status="{{ $course_category->status }}"
                                                        data-image="{{ $course_category->image ? asset($course_category->image) : null }}">
                                                        <i class="fe fe-edit dropdown-item-icon"></i>
                                                        Edit
                                                    </a>
                                                @else
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-bs-target="#editCourseCategory"
                                                        data-id="{{ $course_category->id }}"
                                                        data-name="{{ $course_category->name }}"
                                                        data-icon="{{ $course_category->icon }}"
                                                        data-parent="{{ $course_category->parent_id ? $course_category->parent_id : null }}"
                                                        data-show_at_trending="{{ $course_category->show_at_trending }}"
                                                        data-status="{{ $course_category->status }}"
                                                        data-image="{{ $course_category->image ? asset($course_category->image) : null }}">
                                                        <i class="fe fe-edit dropdown-item-icon"></i>
                                                        Edit
                                                    </a>
                                                @endif
                                                @if ($course_category->subCategories->count() <= 0)
                                                    <a href="#" class="dropdown-item"
                                                        onclick="event.preventDefault(); $('#delete-form-{{ $course_category->id }}').submit();">
                                                        <i class="fe fe-trash dropdown-item-icon"></i>
                                                        Delete
                                                    </a>
                                                    <form
                                                        action="{{ route('admin.course-category.destroy', $course_category->id) }}"
                                                        method="POST" id="delete-form-{{ $course_category->id }}"
                                                        class="d-none">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                @endif
                                            </span>
                                        </span>
                                    </td>
                                </tr>
                                @if ($course_category->subCategories->count() > 0)
                                    @foreach ($course_category->subCategories as $subCategory)
                                        <tr id="collapse{{ $course_category->id }}" class="collapse" style="">
                                            <td>
                                                <div class="form-check">

                                                </div>
                                            </td>
                                            <td>
                                                <a href="#" class="text-inherit">
                                                    <h5 class="mb-0 text-primary-hover ms-3">
                                                        {{ $subCategory->name }}
                                                    </h5>
                                                </a>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $subCategory->show_at_trending ? 'success' : 'danger' }}">
                                                    {{ $subCategory->show_at_trending ? 'Yes' : 'No' }}
                                                </span>
                                            </td>

                                            <td>
                                                <span class="badge bg-{{ $subCategory->status ? 'success' : 'danger' }}">
                                                    {{ $subCategory->status ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            </td>
                                            <td>
                                                {{ $subCategory->created_at->format('d M, Y') }}
                                            </td>
                                            <td>
                                                {{ $subCategory->updated_at->format('d M, Y') }}
                                            </td>
                                            <td>
                                                <span class="dropdown dropstart">
                                                    <a class="btn-icon btn btn-ghost btn-sm rounded-circle" href="#"
                                                        role="button" id="courseDropdown2" data-bs-toggle="dropdown"
                                                        data-bs-offset="-20,20" aria-expanded="false">
                                                        <i class="fe fe-more-vertical"></i>
                                                    </a>
                                                    <span class="dropdown-menu" aria-labelledby="courseDropdown2">
                                                        <span class="dropdown-header">Action</span>
                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                            data-bs-target="#editCourseCategory"
                                                            data-id="{{ $subCategory->id }}"
                                                            data-name="{{ $subCategory->name }}"
                                                            data-icon="{{ $subCategory->icon }}"
                                                            data-parent="{{ $subCategory->parent_id ? $subCategory->parent_id : null }}"
                                                            data-show_at_trending="{{ $subCategory->show_at_trending }}"
                                                            data-status="{{ $subCategory->status }}"
                                                            data-image="{{ $subCategory->image ? asset($subCategory->image) : null }}">
                                                            <i class="fe fe-edit dropdown-item-icon"></i>
                                                            Edit
                                                        </a>
                                                        <a href="#" class="dropdown-item"
                                                            onclick="event.preventDefault(); $('#delete-form-{{ $subCategory->id }}').submit();">
                                                            <i class="fe fe-trash dropdown-item-icon"></i>
                                                            Delete
                                                        </a>
                                                        <form
                                                            action="{{ route('admin.course-category.destroy', $subCategory->id) }}"
                                                            method="POST" id="delete-form-{{ $subCategory->id }}"
                                                            class="d-none">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </span>
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No course category found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('admin.pages.course-category.modals.create')
    @include('admin.pages.course-category.modals.edit')
    @include('admin.pages.course-category.modals.edit-parent')
@endsection
