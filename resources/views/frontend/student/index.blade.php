@extends('frontend.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('content')
    <section class="pt-5">
        <div class="container">
            @include('frontend.student.partials.user-info')
        </div>
    </section>
    <section class="pb-5 py-md-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <!-- Side Navbar -->
                    <ul class="nav nav-lb-tab mb-6" id="tab" role="tablist">
                        {{-- <li class="nav-item ms-0" role="presentation">
                            <a class="nav-link" id="bookmarked-tab" data-bs-toggle="pill" href="#bookmarked" role="tab"
                                aria-controls="bookmarked" aria-selected="false" tabindex="-1">Bookmarked</a>
                        </li> --}}
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="currentlyLearning-tab" data-bs-toggle="pill"
                                href="#currentlyLearning" role="tab" aria-controls="currentlyLearning"
                                aria-selected="true">
                                Learning
                            </a>
                        </li>
                        {{-- <li class="nav-item" role="presentation">
                            <a class="nav-link" id="path-tab" data-bs-toggle="pill" href="#path" role="tab"
                                aria-controls="path" aria-selected="false" tabindex="-1">Path</a>
                        </li> --}}
                    </ul>
                    <!-- Tab content -->
                    <div class="tab-content" id="tabContent">

                        <div class="tab-pane fade active show" id="currentlyLearning" role="tabpanel"
                            aria-labelledby="currentlyLearning-tab">
                            <div class="row">
                                @forelse ($enrolled_courses as $enrolled_course)
                                    @php
                                        $owesRemaining = $enrolled_course->payment_status === 'half_paid';
                                    @endphp
                                    <div class="col-lg-3 col-md-6 col-12">
                                        <!-- Card -->
                                        <div class="card mb-4 card-hover">
                                            @if ($owesRemaining)
                                                <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2"
                                                    style="z-index: 2;">
                                                    Payment Due
                                                </span>
                                            @endif
                                            <a href="{{ route('student.my.course.detail', $enrolled_course->course->id) }}"><img
                                                    src="{{ $enrolled_course->course->thumbnail ? asset($enrolled_course->course->thumbnail) : asset('default-images/course-default.jpg') }}"
                                                    alt="course" class="card-img-top"></a>
                                            <!-- Card body -->
                                            <div class="card-body">
                                                <h3 class="h4 mb-2 text-truncate-line-2"><a
                                                        href="{{ route('student.my.course.detail', $enrolled_course->course->id) }}"
                                                        class="text-inherit text-capitalize">
                                                        {{ $enrolled_course->course->title }}
                                                    </a>
                                                </h3>
                                                <!-- List inline -->
                                                <ul class="mb-3 list-inline">
                                                    <li class="list-inline-item">
                                                        <span class="align-middle">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12"
                                                                height="12" fill="currentColor"
                                                                class="bi bi-clock align-baseline" viewBox="0 0 16 16">
                                                                <path
                                                                    d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z">
                                                                </path>
                                                                <path
                                                                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z">
                                                                </path>
                                                            </svg>
                                                        </span>
                                                        <span>
                                                            {{ $enrolled_course->course->duration }}hrs
                                                        </span>
                                                    </li>
                                                    <li class="list-inline-item">
                                                        <svg class="me-1 mt-n1" width="16" height="16"
                                                            viewBox="0 0 16 16" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <rect x="3" y="8" width="2" height="6" rx="1"
                                                                fill="#754FFE"></rect>
                                                            <rect x="7" y="5" width="2" height="9" rx="1"
                                                                fill="#DBD8E9"></rect>
                                                            <rect x="11" y="2" width="2" height="12" rx="1"
                                                                fill="#DBD8E9"></rect>
                                                        </svg>
                                                        Beginner
                                                    </li>
                                                </ul>
                                                <div class="mt-3 d-flex align-baseline lh-1">
                                                    <span class="fs-6">
                                                        {{-- loop 5 time --}}
                                                        @for ($i = 0; $i < 5; $i++)
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" fill="currentColor"
                                                                class="bi bi-star-fill text-warning" viewBox="0 0 16 16">
                                                                <path
                                                                    d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.32-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.63.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                                </path>
                                                            </svg>
                                                        @endfor
                                                    </span>
                                                    <span class="text-warning mx-1">
                                                        5.0
                                                    </span>
                                                    <span class="fs-6">
                                                        (2,345)
                                                    </span>
                                                </div>

                                                @if ($owesRemaining)
                                                    {{-- Pay Half's 2nd installment is still outstanding — this is
                                                         the one place a student would naturally come looking for
                                                         "my courses" to see what's left to pay, so the amount and
                                                         a direct link to finish paying live right on the card
                                                         instead of only inside the payment flow itself. --}}
                                                    <div
                                                        class="d-flex align-items-center justify-content-between bg-warning-subtle border border-warning-subtle rounded-3 px-3 py-2 mt-3">
                                                        <span class="small text-warning-emphasis fw-semibold">
                                                            ${{ number_format($enrolled_course->remaining_amount, 2) }}
                                                            remaining
                                                        </span>
                                                        {{-- ?pay=1 is the same flag the milestone panel's own
                                                             "Pay Remaining Now" button uses (see
                                                             CONTINUE_PAYMENT_URL in the payment page's script) —
                                                             it skips straight past the "Installment 1 of 2
                                                             Paid!" summary to the actual pay state. The student
                                                             already knows they still owe money (that's why
                                                             they're clicking this from the dashboard), so there's
                                                             nothing that panel would tell them that one more
                                                             click here doesn't. --}}
                                                        <a href="{{ route('student.payment.page', $enrolled_course->invoice_id) }}?pay=1"
                                                            class="btn btn-sm btn-warning fw-semibold">
                                                            Pay Remaining
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                            <!-- Card footer -->
                                            <div class="card-footer">
                                                <div class="row align-items-center g-0">
                                                    <div class="col-auto">
                                                        <img src="{{ !$enrolled_course->course->instructor->image || $enrolled_course->course->instructor->image === 'no-img.jpg' ? asset('default-images/user/both.jpg') : asset($enrolled_course->course->instructor->image) }}"
                                                            class="rounded-circle avatar-xs" alt="avatar">
                                                    </div>
                                                    <div class="col ms-2">
                                                        <span class="text-capitalize">
                                                            {{ $enrolled_course->course->instructor->name }}
                                                        </span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <a href="{{ route('student.my.course.detail', $enrolled_course->course->id) }}"
                                                            class="removeBookmark">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" fill="currentColor"
                                                                class="bi bi-bookmark-fill" viewBox="0 0 16 16">
                                                                <path
                                                                    d="M2 2v13.5a.5.5 0 0 0 .74.439L8 13.069l5.26 2.87A.5.5 0 0 0 14 15.5V2a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z">
                                                                </path>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>
                                                {{-- <div class="progress mt-3" style="height: 5px">
                                                    <div class="progress-bar bg-success" role="progressbar"
                                                        style="width: 45%" aria-valuenow="45" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="row">
                                        <div class="offset-lg-3 col-lg-6 col-md-12 col-12 text-center mt-5">
                                            <p>You’ve reached the end of the list</p>
                                        </div>
                                    </div> --}}
                                @empty
                                    <div class="offset-lg-3 col-lg-6 col-md-12 col-12 text-center mt-5">
                                        <p>You have not enrolled in any course yet.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
