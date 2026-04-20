<div class="tab-pane fade" id="students" role="tabpanel" aria-labelledby="students-tab">
    <div class="col-lg-12 col-md-9 col-12">
        <!-- Card -->
        <div class="card mb-4">
            <!-- Card body -->
            <div class="p-4 d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0">Students</h3>
                    <span>Meet people taking your course.</span>
                </div>
                <!-- Nav -->
                <div class="nav btn-group flex-nowrap" role="tablist">
                    <button class="btn btn-outline-secondary" data-bs-toggle="tab" data-bs-target="#tabPaneGrid"
                        role="tab" aria-controls="tabPaneGrid" aria-selected="false" tabindex="-1">
                        <span class="fe fe-grid"></span>
                    </button>
                    <button class="btn btn-outline-secondary active" data-bs-toggle="tab" data-bs-target="#tabPaneList"
                        role="tab" aria-controls="tabPaneList" aria-selected="true">
                        <span class="fe fe-list"></span>
                    </button>
                </div>
            </div>
        </div>
        <!-- Tab content -->
        <div class="tab-content">
            <div class="tab-pane fade pb-4" id="tabPaneGrid" role="tabpanel" aria-labelledby="tabPaneGrid">
                <div class="row">
                    {{-- <div class="col-xl-12 col-lg-12 col-12 mb-3">
                        <!-- Content -->
                        <div class="row">
                            <div class="col pe-0">
                                <!-- Form -->
                                <form>
                                    <input type="search" class="form-control" placeholder="Search by Name">
                                </form>
                            </div>
                            <!-- Button -->
                            <div class="col-auto">
                                <a href="#" class="btn btn-secondary">
                                    Export CSV
                                </a>
                            </div>
                        </div>
                    </div> --}}
                    @forelse ($students as $student)
                        <div class="col-lg-4 col-md-6 col-12">
                            <!-- Card -->
                            <div class="card mb-4">
                                <!-- Card body -->
                                <div class="card-body">
                                    <div class="text-center">
                                        <img src=" {{ $student->image == 'no-img.jpg' ? asset('\default-images\user\both.jpg') : asset($student->image) }}"
                                            class="rounded-circle avatar-xl mb-3" alt="avatar">
                                        <h4 class="mb-1">
                                            {{ $student->name }}
                                        </h4>
                                        <p class="mb-0">
                                            <i class="fe fe-map-pin me-1"></i>
                                            {{ $student->location ?? 'Unknown' }}
                                        </p>
                                        {{-- <a href="#"
                                                                            class="btn btn-sm btn-outline-secondarymt-3">Message</a> --}}
                                    </div>
                                    <div class="d-flex justify-content-between  py-2 mt-4 fs-6">
                                        <span>Enrolled</span>
                                        <span class="text-dark">
                                            {{ $student->pivot->created_at->format('d M, Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="card mb-4">
                                <div class="card-body text-center">
                                    <h4 class="mb-0">No students enrolled yet.</h4>
                                </div>
                            </div>
                        </div>
                    @endforelse
                    <div class="col-lg-12 col-md-12 col-12">
                        <!-- Pagination -->
                        <nav>
                            <ul class="pagination justify-content-center mb-0">

                                {{-- Previous --}}
                                <li class="page-item {{ $students->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link mx-1 rounded" href="{{ $students->previousPageUrl() ?? '#' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                                            fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z">
                                            </path>
                                        </svg>
                                    </a>
                                </li>

                                {{-- Page Numbers --}}
                                @for ($i = 1; $i <= $students->lastPage(); $i++)
                                    <li class="page-item {{ $students->currentPage() == $i ? 'active' : '' }}">
                                        <a class="page-link mx-1 rounded" href="{{ $students->url($i) }}">
                                            {{ $i }}
                                        </a>
                                    </li>
                                @endfor

                                {{-- Next --}}
                                <li class="page-item {{ !$students->hasMorePages() ? 'disabled' : '' }}">
                                    <a class="page-link mx-1 rounded" href="{{ $students->nextPageUrl() ?? '#' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                                            fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z">
                                            </path>
                                        </svg>
                                    </a>
                                </li>

                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <!-- Tab pane -->
            <div class="tab-pane fade active show" id="tabPaneList" role="tabpanel" aria-labelledby="tabPaneList">
                <div class="card">
                    {{-- <div class="card-header border-bottom-0">
                        <div class="row">
                            <div class="col pe-0">
                                <form>
                                    <input type="search" class="form-control" placeholder="Search by Name">
                                </form>
                            </div>
                            <div class="col-auto">
                                <a href="#" class="btn btn-secondary">Export
                                    CSV</a>
                            </div>
                        </div>
                    </div> --}}
                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover table-centered">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Enrolled</th>
                                    <th>Locations</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($students as $student)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="
                                                                                    {{ $student->image == 'no-img.jpg' ? asset('\default-images\user\both.jpg') : asset($student->image) }}"
                                                    alt="" class="rounded-circle avatar-md me-2">
                                                <h5 class="mb-0">
                                                    {{ $student->name }}
                                                </h5>
                                            </div>
                                        </td>
                                        <td>
                                            {{ $student->pivot->created_at->format('d M, Y') }}
                                        </td>
                                        <td>
                                            <span class="fs-6">
                                                <i class="fe fe-map-pin me-1"></i>
                                                {{ $student->location ?? 'Unknown' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="dropdown dropstart">
                                                <a class="btn-icon btn btn-ghost btn-sm rounded-circle" href="#"
                                                    role="button" id="courseDropdown" data-bs-toggle="dropdown"
                                                    data-bs-offset="-20,20" aria-expanded="false">
                                                    <i class="fe fe-more-vertical"></i>
                                                </a>
                                                <span class="dropdown-menu" aria-labelledby="courseDropdown">
                                                    <span class="dropdown-header">Setting</span>
                                                    <a class="dropdown-item" href="#">
                                                        <i class="fe fe-edit dropdown-item-icon"></i>
                                                        Edit
                                                    </a>
                                                    <a class="dropdown-item" href="#">
                                                        <i class="fe fe-trash dropdown-item-icon"></i>
                                                        Remove
                                                    </a>
                                                </span>
                                            </span>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            No students enrolled yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="pt-2 pb-4">
                        <nav>
                            <ul class="pagination justify-content-center mb-0">

                                {{-- Previous --}}
                                <li class="page-item {{ $students->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link mx-1 rounded"
                                        href="{{ $students->previousPageUrl() ?? '#' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                                            fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z" />
                                        </svg>
                                    </a>
                                </li>

                                {{-- Page Numbers --}}
                                @for ($i = 1; $i <= $students->lastPage(); $i++)
                                    <li class="page-item {{ $students->currentPage() == $i ? 'active' : '' }}">
                                        <a class="page-link mx-1 rounded" href="{{ $students->url($i) }}">
                                            {{ $i }}
                                        </a>
                                    </li>
                                @endfor

                                {{-- Next --}}
                                <li class="page-item {{ !$students->hasMorePages() ? 'disabled' : '' }}">
                                    <a class="page-link mx-1 rounded" href="{{ $students->nextPageUrl() ?? '#' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                                            fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z" />
                                        </svg>
                                    </a>
                                </li>

                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            {{-- tab for taking student attendance --}}
        </div>
    </div>
</div>
