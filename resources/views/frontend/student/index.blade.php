@extends('frontend.layouts.master')
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
                        <li class="nav-item ms-0" role="presentation">
                            <a class="nav-link" id="bookmarked-tab" data-bs-toggle="pill" href="#bookmarked" role="tab"
                                aria-controls="bookmarked" aria-selected="false" tabindex="-1">Bookmarked</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="currentlyLearning-tab" data-bs-toggle="pill"
                                href="#currentlyLearning" role="tab" aria-controls="currentlyLearning"
                                aria-selected="true">
                                Learning
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="path-tab" data-bs-toggle="pill" href="#path" role="tab"
                                aria-controls="path" aria-selected="false" tabindex="-1">Path</a>
                        </li>
                    </ul>
                    <!-- Tab content -->
                    <div class="tab-content" id="tabContent">
                        <div class="tab-pane fade" id="bookmarked" role="tabpanel" aria-labelledby="bookmarked-tab">
                            <div class="row">
                                <div class="col-lg-3 col-md-6 col-12">
                                    <!-- Card -->
                                    <div class="card mb-4 card-hover">
                                        <a href="#"><img src="/frontend/assets/images/course/course-react.jpg"
                                                alt="course" class="card-img-top"></a>
                                        <!-- Card body -->
                                        <div class="card-body">
                                            <h3 class="h4 mb-2 text-truncate-line-2"><a href="#"
                                                    class="text-inherit">How to easily create a website with React</a></h3>
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
                                                    <span>3h 56m</span>
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
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                </span>
                                                <span class="text-warning mx-1">4.5</span>
                                                <span class="fs-6">(9,300)</span>
                                            </div>
                                        </div>
                                        <!-- Card footer -->
                                        <div class="card-footer">
                                            <div class="row align-items-center g-0">
                                                <div class="col-auto">
                                                    <img src="/frontend/assets/images/avatar/avatar-3.jpg"
                                                        class="rounded-circle avatar-xs" alt="avatar">
                                                </div>
                                                <div class="col ms-2">
                                                    <span>Morris Mccoy</span>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="removeBookmark">
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
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-12">
                                    <!-- Card -->
                                    <div class="card mb-4 card-hover">
                                        <a href="#"><img src="/frontend/assets/images/course/course-graphql.jpg"
                                                alt="course" class="card-img-top"></a>
                                        <!-- Card body -->
                                        <div class="card-body">
                                            <h3 class="h4 mb-2 text-truncate-line-2"><a href="#"
                                                    class="text-inherit">GraphQL: introduction to graphQL for beginners</a>
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
                                                    <span>2h 46m</span>
                                                </li>
                                                <li class="list-inline-item">
                                                    <svg class="me-1 mt-n1" width="16" height="16"
                                                        viewBox="0 0 16 16" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <rect x="3" y="8" width="2" height="6" rx="1"
                                                            fill="#754FFE"></rect>
                                                        <rect x="7" y="5" width="2" height="9" rx="1"
                                                            fill="#754FFE"></rect>
                                                        <rect x="11" y="2" width="2" height="12" rx="1"
                                                            fill="#754FFE"></rect>
                                                    </svg>
                                                    Advance
                                                </li>
                                            </ul>
                                            <div class="mt-3 d-flex align-baseline lh-1">
                                                <span class="fs-6">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                </span>
                                                <span class="text-warning mx-1">4.5</span>
                                                <span class="fs-6">(7,800)</span>
                                            </div>
                                        </div>
                                        <!-- Card footer -->
                                        <div class="card-footer">
                                            <div class="row align-items-center g-0">
                                                <div class="col-auto">
                                                    <img src="/frontend/assets/images/avatar/avatar-2.jpg"
                                                        class="rounded-circle avatar-xs" alt="avatar">
                                                </div>
                                                <div class="col ms-2">
                                                    <span>Ted Hawkins</span>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="removeBookmark">
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
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-12">
                                    <!-- Card -->
                                    <div class="card mb-4 card-hover">
                                        <a href="#"><img src="/frontend/assets/images/course/course-angular.jpg"
                                                alt="course" class="card-img-top"></a>
                                        <!-- Card body -->
                                        <div class="card-body">
                                            <h3 class="h4 mb-2 text-truncate-line-2"><a href="#"
                                                    class="text-inherit">Angular - the complete guide for beginner</a></h3>
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
                                                    <span>1h 30m</span>
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
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                </span>
                                                <span class="text-warning mx-1">4.5</span>
                                                <span class="fs-6">(9,300)</span>
                                            </div>
                                        </div>
                                        <!-- Card footer -->
                                        <div class="card-footer">
                                            <div class="row align-items-center g-0">
                                                <div class="col-auto">
                                                    <img src="/frontend/assets/images/avatar/avatar-4.jpg"
                                                        class="rounded-circle avatar-xs" alt="avatar">
                                                </div>
                                                <div class="col ms-2">
                                                    <span>Juanita Bell</span>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="removeBookmark">
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
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-12">
                                    <!-- Card -->
                                    <div class="card mb-4 card-hover">
                                        <a href="#"><img src="/frontend/assets/images/course/course-python.jpg"
                                                alt="course" class="card-img-top"></a>
                                        <!-- Card body -->
                                        <div class="card-body">
                                            <h3 class="h4 mb-2 text-truncate-line-2"><a href="#"
                                                    class="text-inherit">The Python Course: build web application</a></h3>
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
                                                    <span>2h 30m</span>
                                                </li>
                                                <li class="list-inline-item">
                                                    <svg class="me-1 mt-n1" width="16" height="16"
                                                        viewBox="0 0 16 16" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <rect x="3" y="8" width="2" height="6" rx="1"
                                                            fill="#754FFE"></rect>
                                                        <rect x="7" y="5" width="2" height="9" rx="1"
                                                            fill="#754FFE"></rect>
                                                        <rect x="11" y="2" width="2" height="12" rx="1"
                                                            fill="#DBD8E9"></rect>
                                                    </svg>
                                                    Intermediate
                                                </li>
                                            </ul>
                                            <div class="mt-3 d-flex align-baseline lh-1">
                                                <span class="fs-6">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                </span>
                                                <span class="text-warning mx-1">4.5</span>
                                                <span class="fs-6">(3,245)</span>
                                            </div>
                                        </div>
                                        <!-- Card Footer -->
                                        <div class="card-footer">
                                            <div class="row align-items-center g-0">
                                                <div class="col-auto">
                                                    <img src="/frontend/assets/images/avatar/avatar-5.jpg"
                                                        class="rounded-circle avatar-xs" alt="avatar">
                                                </div>
                                                <div class="col ms-2">
                                                    <span>Claire Robertson</span>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="removeBookmark">
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-6 col-12">
                                    <!-- Card -->
                                    <div class="card mb-4 card-hover">
                                        <a href="#"><img src="/frontend/assets/images/course/course-gatsby.jpg"
                                                alt="course" class="card-img-top"></a>
                                        <!-- Card body -->
                                        <div class="card-body">
                                            <h3 class="h4 mb-2 text-truncate-line-2"><a href="#"
                                                    class="text-inherit">Gatsby JS: build blog with GraphQL and React</a>
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
                                                    <span>3h 56m</span>
                                                </li>
                                                <li class="list-inline-item">
                                                    <svg class="me-1 mt-n1" width="16" height="16"
                                                        viewBox="0 0 16 16" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <rect x="3" y="8" width="2" height="6" rx="1"
                                                            fill="#754FFE">
                                                            <rect x="7" y="5" width="2" height="9"
                                                                rx="1" fill="#DBD8E9">
                                                                <rect x="11" y="2" width="2" height="12"
                                                                    rx="1" fill="#DBD8E9">
                                                                </rect>
                                                            </rect>
                                                        </rect>
                                                    </svg>
                                                    Beginner
                                                </li>
                                            </ul>
                                            <div class="mt-3 d-flex align-baseline lh-1">
                                                <span class="fs-6">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                </span>
                                                <span class="text-warning mx-1">4.5</span>
                                                <span class="fs-6">(5,300)</span>
                                            </div>
                                        </div>
                                        <!-- Card Footer -->
                                        <div class="card-footer">
                                            <div class="row align-items-center g-0">
                                                <div class="col-auto">
                                                    <img src="/frontend/assets/images/avatar/avatar-5.jpg"
                                                        class="rounded-circle avatar-xs" alt="avatar">
                                                </div>
                                                <div class="col ms-2">
                                                    <span>Morris Mccoy</span>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="removeBookmark">
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
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-12">
                                    <!-- Card -->
                                    <div class="card mb-4 card-hover">
                                        <a href="#"><img src="/frontend/assets/images/course/course-javascript.jpg"
                                                alt="course" class="card-img-top"></a>
                                        <!-- Card body -->
                                        <div class="card-body">
                                            <h3 class="h4 mb-2 text-truncate-line-2"><a href="#"
                                                    class="text-inherit">JavaScript: modern javaScript from the
                                                    beginning</a></h3>
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
                                                    <span>2h 46m</span>
                                                </li>
                                                <li class="list-inline-item">
                                                    <svg class="me-1 mt-n1" width="16" height="16"
                                                        viewBox="0 0 16 16" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <rect x="3" y="8" width="2" height="6" rx="1"
                                                            fill="#754FFE">
                                                            <rect x="7" y="5" width="2" height="9"
                                                                rx="1" fill="#754FFE">
                                                                <rect x="11" y="2" width="2" height="12"
                                                                    rx="1" fill="#754FFE">
                                                                </rect>
                                                            </rect>
                                                        </rect>
                                                    </svg>
                                                    Advance
                                                </li>
                                            </ul>
                                            <div class="mt-3 d-flex align-baseline lh-1">
                                                <span class="fs-6">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                </span>
                                                <span class="text-warning mx-1">4.5</span>
                                                <span class="fs-6">(9,300)</span>
                                            </div>
                                        </div>
                                        <!-- Card Footer -->
                                        <div class="card-footer">
                                            <div class="row align-items-center g-0">
                                                <div class="col-auto">
                                                    <img src="/frontend/assets/images/avatar/avatar-6.jpg"
                                                        class="rounded-circle avatar-xs" alt="avatar">
                                                </div>
                                                <div class="col ms-2">
                                                    <span>Ted Hawkins</span>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="removeBookmark">
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
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-12">
                                    <!-- Card -->
                                    <div class="card mb-4 card-hover">
                                        <a href="#"><img src="/frontend/assets/images/course/course-css.jpg"
                                                alt="course" class="card-img-top"></a>
                                        <!-- Card body -->
                                        <div class="card-body">
                                            <h3 class="h4 mb-2 text-truncate-line-2"><a href="#"
                                                    class="text-inherit">CSS: ultimate CSS course from beginner to
                                                    advanced</a></h3>
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
                                                    <span>1h 30m</span>
                                                </li>
                                                <li class="list-inline-item">
                                                    <svg class="me-1 mt-n1" width="16" height="16"
                                                        viewBox="0 0 16 16" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <rect x="3" y="8" width="2" height="6" rx="1"
                                                            fill="#754FFE">
                                                            <rect x="7" y="5" width="2" height="9"
                                                                rx="1" fill="#DBD8E9">
                                                                <rect x="11" y="2" width="2" height="12"
                                                                    rx="1" fill="#DBD8E9">
                                                                </rect>
                                                            </rect>
                                                        </rect>
                                                    </svg>
                                                    Beginner
                                                </li>
                                            </ul>
                                            <div class="mt-3 d-flex align-baseline lh-1">
                                                <span class="fs-6">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                </span>
                                                <span class="text-warning mx-1">4.5</span>
                                                <span class="fs-6">(5,586)</span>
                                            </div>
                                        </div>
                                        <!-- Card Footer -->
                                        <div class="card-footer">
                                            <div class="row align-items-center g-0">
                                                <div class="col-auto">
                                                    <img src="/frontend/assets/images/avatar/avatar-7.jpg"
                                                        class="rounded-circle avatar-xs" alt="avatar">
                                                </div>
                                                <div class="col ms-2">
                                                    <span>Juanita Bell</span>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="removeBookmark">
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
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-12">
                                    <!-- Card -->
                                    <div class="card mb-4 card-hover">
                                        <a href="#"><img src="/frontend/assets/images/course/course-wordpress.jpg"
                                                alt="course" class="card-img-top"></a>
                                        <!-- Card body -->
                                        <div class="card-body">
                                            <h3 class="h4 mb-2 text-truncate-line-2"><a href="#"
                                                    class="text-inherit">Wordpress: complete WordPress theme &amp; plugin
                                                    dvelopment course</a></h3>
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
                                                    <span>2h 30m</span>
                                                </li>
                                                <li class="list-inline-item">
                                                    <svg class="me-1 mt-n1" width="16" height="16"
                                                        viewBox="0 0 16 16" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <rect x="3" y="8" width="2" height="6" rx="1"
                                                            fill="#754FFE">
                                                            <rect x="7" y="5" width="2" height="9"
                                                                rx="1" fill="#754FFE">
                                                                <rect x="11" y="2" width="2" height="12"
                                                                    rx="1" fill="#DBD8E9">
                                                                </rect>
                                                            </rect>
                                                        </rect>
                                                    </svg>
                                                    Intermediate
                                                </li>
                                            </ul>
                                            <div class="mt-3 d-flex align-baseline lh-1">
                                                <span class="fs-6">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                </span>
                                                <span class="text-warning mx-1">4.5</span>
                                                <span class="fs-6">(6,245)</span>
                                            </div>
                                        </div>
                                        <!-- Card footer -->
                                        <div class="card-footer">
                                            <div class="row align-items-center g-0">
                                                <div class="col-auto">
                                                    <img src="/frontend/assets/images/avatar/avatar-8.jpg"
                                                        class="rounded-circle avatar-xs" alt="avatar">
                                                </div>
                                                <div class="col ms-2">
                                                    <span>Claire Robertson</span>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="removeBookmark">
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="offset-lg-3 col-lg-6 col-md-12 col-12 text-center mt-5">
                                    <p>You’ve reached the end of the list</p>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade active show" id="currentlyLearning" role="tabpanel"
                            aria-labelledby="currentlyLearning-tab">
                            <div class="row">
                                <div class="col-lg-3 col-md-6 col-12">
                                    <!-- Card -->
                                    <div class="card mb-4 card-hover">
                                        <a href="#"><img src="/frontend/assets/images/course/course-react.jpg"
                                                alt="course" class="card-img-top"></a>
                                        <!-- Card body -->
                                        <div class="card-body">
                                            <h3 class="h4 mb-2 text-truncate-line-2"><a href="#"
                                                    class="text-inherit">How to easily create a website with React</a></h3>
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
                                                    <span>3h 56m</span>
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
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                </span>
                                                <span class="text-warning mx-1">4.5</span>
                                                <span class="fs-6">(6,300)</span>
                                            </div>
                                        </div>
                                        <!-- Card footer -->
                                        <div class="card-footer">
                                            <div class="row align-items-center g-0">
                                                <div class="col-auto">
                                                    <img src="/frontend/assets/images/avatar/avatar-6.jpg"
                                                        class="rounded-circle avatar-xs" alt="avatar">
                                                </div>
                                                <div class="col ms-2">
                                                    <span>Morris Mccoy</span>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="removeBookmark">
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
                                            <div class="progress mt-3" style="height: 5px">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: 45%" aria-valuenow="45" aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-12">
                                    <!-- Card -->
                                    <div class="card mb-4 card-hover">
                                        <a href="#"><img src="/frontend/assets/images/course/course-graphql.jpg"
                                                alt="course" class="card-img-top"></a>
                                        <!-- Card body -->
                                        <div class="card-body">
                                            <h3 class="h4 mb-2 text-truncate-line-2"><a href="#"
                                                    class="text-inherit">GraphQL: introduction to graphQL for beginners</a>
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
                                                    <span>2h 46m</span>
                                                </li>
                                                <li class="list-inline-item">
                                                    <svg class="me-1 mt-n1" width="16" height="16"
                                                        viewBox="0 0 16 16" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <rect x="3" y="8" width="2" height="6" rx="1"
                                                            fill="#754FFE"></rect>
                                                        <rect x="7" y="5" width="2" height="9" rx="1"
                                                            fill="#754FFE"></rect>
                                                        <rect x="11" y="2" width="2" height="12" rx="1"
                                                            fill="#754FFE"></rect>
                                                    </svg>
                                                    Advance
                                                </li>
                                            </ul>
                                            <div class="mt-3 d-flex align-baseline lh-1">
                                                <span class="fs-6">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                </span>
                                                <span class="text-warning mx-1">4.5</span>
                                                <span class="fs-6">(7,800)</span>
                                            </div>
                                        </div>
                                        <!-- Card footer -->
                                        <div class="card-footer">
                                            <div class="row align-items-center g-0">
                                                <div class="col-auto">
                                                    <img src="/frontend/assets/images/avatar/avatar-7.jpg"
                                                        class="rounded-circle avatar-xs" alt="avatar">
                                                </div>
                                                <div class="col ms-2">
                                                    <span>Ted Hawkins</span>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="removeBookmark">
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
                                            <div class="progress mt-3" style="height: 5px">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: 95%" aria-valuenow="95" aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-12">
                                    <!-- Card -->
                                    <div class="card mb-4 card-hover">
                                        <a href="#"><img src="/frontend/assets/images/course/course-angular.jpg"
                                                alt="course" class="card-img-top"></a>
                                        <!-- Card body -->
                                        <div class="card-body">
                                            <h3 class="h4 mb-2 text-truncate-line-2"><a href="#"
                                                    class="text-inherit">Angular - the complete guide for beginner</a>
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
                                                    <span>1h 30m</span>
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
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-star-fill text-warning"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12"
                                                        height="12" fill="currentColor"
                                                        class="bi bi-star-fill text-warning" viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12"
                                                        height="12" fill="currentColor"
                                                        class="bi bi-star-fill text-warning" viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12"
                                                        height="12" fill="currentColor"
                                                        class="bi bi-star-fill text-warning" viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                </span>
                                                <span class="text-warning mx-1">4.5</span>
                                                <span class="fs-6">(5,410)</span>
                                            </div>
                                        </div>
                                        <!-- Card footer -->
                                        <div class="card-footer">
                                            <div class="row align-items-center g-0">
                                                <div class="col-auto">
                                                    <img src="/frontend/assets/images/avatar/avatar-8.jpg"
                                                        class="rounded-circle avatar-xs" alt="avatar">
                                                </div>
                                                <div class="col ms-2">
                                                    <span>Juanita Bell</span>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="removeBookmark">
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
                                            <div class="progress mt-3" style="height: 5px">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: 65%" aria-valuenow="65" aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-12">
                                    <!-- Card -->
                                    <div class="card mb-4 card-hover">
                                        <a href="#"><img src="/frontend/assets/images/course/course-python.jpg"
                                                alt="course" class="card-img-top"></a>
                                        <!-- Card body -->
                                        <div class="card-body">
                                            <h3 class="h4 mb-2 text-truncate-line-2"><a href="#"
                                                    class="text-inherit">The Python Course: build web application</a></h3>
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
                                                    <span>2h 30m</span>
                                                </li>
                                                <li class="list-inline-item">
                                                    <svg class="me-1 mt-n1" width="16" height="16"
                                                        viewBox="0 0 16 16" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <rect x="3" y="8" width="2" height="6" rx="1"
                                                            fill="#754FFE"></rect>
                                                        <rect x="7" y="5" width="2" height="9" rx="1"
                                                            fill="#754FFE"></rect>
                                                        <rect x="11" y="2" width="2" height="12"
                                                            rx="1" fill="#DBD8E9"></rect>
                                                    </svg>
                                                    Intermediate
                                                </li>
                                            </ul>
                                            <div class="mt-3 d-flex align-baseline lh-1">
                                                <span class="fs-6">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12"
                                                        height="12" fill="currentColor"
                                                        class="bi bi-star-fill text-warning" viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12"
                                                        height="12" fill="currentColor"
                                                        class="bi bi-star-fill text-warning" viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12"
                                                        height="12" fill="currentColor"
                                                        class="bi bi-star-fill text-warning" viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12"
                                                        height="12" fill="currentColor"
                                                        class="bi bi-star-fill text-warning" viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12"
                                                        height="12" fill="currentColor"
                                                        class="bi bi-star-fill text-warning" viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                </span>
                                                <span class="text-warning mx-1">4.5</span>
                                                <span class="fs-6">(9,300)</span>
                                            </div>
                                        </div>
                                        <!-- Card footer -->
                                        <div class="card-footer">
                                            <div class="row align-items-center g-0">
                                                <div class="col-auto">
                                                    <img src="/frontend/assets/images/avatar/avatar-9.jpg"
                                                        class="rounded-circle avatar-xs" alt="avatar">
                                                </div>
                                                <div class="col ms-2">
                                                    <span>Claire Robertson</span>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="removeBookmark">
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
                                            <div class="progress mt-3" style="height: 5px">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: 75%" aria-valuenow="75" aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-12">
                                    <!-- Card -->
                                    <div class="card mb-4 card-hover">
                                        <a href="#"><img src="/frontend/assets/images/course/course-python.jpg"
                                                alt="course" class="card-img-top"></a>
                                        <!-- Card body -->
                                        <div class="card-body">
                                            <h3 class="h4 mb-2 text-truncate-line-2"><a href="#"
                                                    class="text-inherit">The Python Course: build web application</a></h3>
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
                                                    <span>2h 30m</span>
                                                </li>
                                                <li class="list-inline-item">
                                                    <svg class="me-1 mt-n1" width="16" height="16"
                                                        viewBox="0 0 16 16" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <rect x="3" y="8" width="2" height="6" rx="1"
                                                            fill="#754FFE"></rect>
                                                        <rect x="7" y="5" width="2" height="9" rx="1"
                                                            fill="#754FFE"></rect>
                                                        <rect x="11" y="2" width="2" height="12"
                                                            rx="1" fill="#DBD8E9"></rect>
                                                    </svg>
                                                    Intermediate
                                                </li>
                                            </ul>
                                            <div class="mt-3 d-flex align-baseline lh-1">
                                                <span class="fs-6">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12"
                                                        height="12" fill="currentColor"
                                                        class="bi bi-star-fill text-warning" viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12"
                                                        height="12" fill="currentColor"
                                                        class="bi bi-star-fill text-warning" viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12"
                                                        height="12" fill="currentColor"
                                                        class="bi bi-star-fill text-warning" viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12"
                                                        height="12" fill="currentColor"
                                                        class="bi bi-star-fill text-warning" viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12"
                                                        height="12" fill="currentColor"
                                                        class="bi bi-star-fill text-warning" viewBox="0 0 16 16">
                                                        <path
                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                        </path>
                                                    </svg>
                                                </span>
                                                <span class="text-warning mx-1">4.5</span>
                                                <span class="fs-6">(13,245)</span>
                                            </div>
                                        </div>
                                        <!-- Card footer -->
                                        <div class="card-footer">
                                            <div class="row align-items-center g-0">
                                                <div class="col-auto">
                                                    <img src="/frontend/assets/images/avatar/avatar-9.jpg"
                                                        class="rounded-circle avatar-xs" alt="avatar">
                                                </div>
                                                <div class="col ms-2">
                                                    <span>Claire Robertson</span>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="removeBookmark">
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
                                            <div class="progress mt-3" style="height: 5px">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: 75%" aria-valuenow="75" aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="offset-lg-3 col-lg-6 col-md-12 col-12 text-center mt-5">
                                    <p>You’ve reached the end of the list</p>
                                </div>
                            </div>
                        </div>
                        <!-- Path -->
                        <div class="tab-pane fade" id="path" role="tabpanel" aria-labelledby="path-tab">
                            <div class="row d-flex justify-content-center text-center">
                                <div class="col-xl-5 col-lg-5 col-md-12 col-12">
                                    <div class="py-6">
                                        <img src="/frontend/assets/images/svg/path-img.svg" alt="path"
                                            class="img-fluid">
                                        <div class="mt-4">
                                            <h2 class="display-4 fw-bold">Coming Soon</h2>
                                            <p class="mb-5">The designer working on our design so for now we schedule it
                                                to come soon. we release it soon for you. Thank you for watching.</p>
                                            <a href="../index.html" class="btn btn-primary">Back To Dashboard</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
