@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('content')
    <!--  Owl carousel -->
    <div class="owl-carousel counter-carousel owl-theme">
        <div class="item">
            <div class="card border-0 zoom-in bg-light-primary shadow-none">
                <div class="card-body">
                    <div class="text-center">
                        <img src="{{ asset('/admin/assets/dist/images/svgs/icon-user-male.svg') }}" width="50"
                            height="50" class="mb-3" alt="" />
                        <p class="fw-semibold fs-3 text-primary mb-1"> Staffs </p>
                        <h5 class="fw-semibold text-primary mb-0">
                            {{ $staffs_count ?? 0 }}
                        </h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="item">
            <div class="card border-0 zoom-in bg-light-warning shadow-none">
                <div class="card-body">
                    <div class="text-center">
                        <img src="{{ asset('/admin/assets/dist/images/svgs/icon-connect.svg') }}" width="50"
                            height="50" class="mb-3" alt="" />
                        <p class="fw-semibold fs-3 text-warning mb-1">Reports</p>
                        <h5 class="fw-semibold text-warning mb-0">
                            {{ $reports_count ?? 0 }}
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Top Performers -->
        <div class="col-lg-12 d-flex align-items-strech">
            <div class="card w-100">
                <div class="card-body">
                    <div class="d-sm-flex d-block align-items-center justify-content-between mb-7">
                        <div class="mb-3 mb-sm-0">
                            <h5 class="card-title fw-semibold">
                                Recent Students
                            </h5>
                            <p class="card-subtitle mb-0">
                                {{ $students_count ?? 0 }} students registered by you.
                            </p>
                        </div>
                        <div>
                            {{-- Rigister --}}
                            <a href="{{ route('staff.student.registration') }}" class="btn btn-primary">
                                {{-- <i class="fa-solid fa-plus"></i> --}}
                                Rigistration
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle text-nowrap mb-0">
                            <thead>
                                <tr class="text-muted fw-semibold">
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Registered At</th>
                                </tr>
                            </thead>
                            <tbody class="border-top">
                                @foreach ($students as $student)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="{{ asset($student->image == 'no-img.jpg' ? '\default-images\user\both.jpg' : $student->image) }}"
                                                    alt="" class="rounded-circle" width="40" height="40" />
                                                <div class="d-flex flex-column">
                                                    <span class="fw-semibold">{{ $student->name }}</span>
                                                    <small class="text-muted">{{ $student->role }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $student->email }}</td>
                                        <td>{{ $student->phone }}</td>
                                        <td>{{ $student->created_at->format('d M Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
