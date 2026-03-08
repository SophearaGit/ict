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
                        <h5 class="fw-semibold text-primary mb-0">96</h5>
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
                        <h5 class="fw-semibold text-warning mb-0">59</h5>
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
                                Your Registered Students ( {{ $students_count }} )
                            </h5>
                            <p class="card-subtitle mb-0">
                                View and manage the students you have registered.
                            </p>
                        </div>
                        <div>
                            {{-- <select class="form-select">
                                <option value="1">March 2023</option>
                                <option value="2">April 2023</option>
                                <option value="3">May 2023</option>
                                <option value="4">June 2023</option>
                            </select> --}}
                            {{-- register student --}}
                            <a href="{{ route('staff.student.registration') }}" class="btn btn-outline-primary">
                                {{-- tablr icon class  --}}
                                <i class="ti ti-user-plus me-2"></i>
                                Register Student
                            </a>
                            <a href="javascript:;" class="btn btn-primary">
                                <i class="ti ti-eye me-2"></i>
                                View All
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle text-nowrap mb-0">
                            <thead>
                                <tr class="text-muted fw-semibold">
                                    <th scope="col" class="ps-0">Name</th>

                                </tr>
                            </thead>
                            <tbody class="border-top">
                                @forelse ($students as $student)
                                    <tr>
                                        <td class="ps-0">
                                            <div class="d-flex align-items-center">
                                                <div class="me-2 pe-1">
                                                    <img src="{{ asset($student->image == 'no-img.jpg' ? '\default-images\user\both.jpg' : $student->image) }}"
                                                        class="rounded-circle" width="40" height="40" alt="">
                                                </div>
                                                <div>
                                                    <h6 class="fw-semibold mb-1">
                                                        {{ $student->name }}
                                                    </h6>
                                                    <p class="fs-2 mb-0 text-muted">
                                                        {{ $student->email }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            No students registered yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
