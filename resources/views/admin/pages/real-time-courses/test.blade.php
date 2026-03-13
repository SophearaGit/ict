   <div class="row">
       <div class="col-lg-12 col-md-12 col-12">
           <!-- Tab -->
           <div class="tab-content">
               <!-- Tab pane -->
               <div class="tab-pane fade show active" id="tabPaneGrid" role="tabpanel" aria-labelledby="tabPaneGrid">
                   <div class="mb-4">
                       <input type="search" class="form-control" placeholder="Search Course">
                   </div>
                   <div class="row">
                       @forelse ($courses as $course)
                           <div class="col-lg-3 col-md-6 col-12">
                               <!-- Card -->
                               <div class="card mb-4 card-hover">
                                   <a href="course-single.html"><img
                                           src="{{ asset($course->thumbnail == '' ? '/default-images/noImg/no-thumbnail.jpg' : $course->thumbnail) }}"
                                           alt="course" class="card-img-top"
                                           style="height: 230px; object-fit: cover;"></a>
                                   <!-- Card body -->
                                   <div class="card-body">
                                       <h4 class="mb-2 text-truncate-line-2">
                                           <a href="course-single.html" class="text-inherit">
                                               {{ $course->title }}
                                           </a>
                                       </h4>
                                       <div class="d-flex justify-content-between border-bottom py-2 mt-2">
                                           <span>
                                               Created At
                                           </span>
                                           <span class="text-dark">
                                               {{ $course->created_at->format('d M, Y') }}
                                           </span>
                                       </div>
                                       <div class="d-flex justify-content-between border-bottom py-2 mt-2">
                                           <span>
                                               Scehdule
                                           </span>
                                           <span class="text-dark">
                                               @php
                                                   $days = collect(explode('-', $course->schedule->study_day))
                                                       ->map(
                                                           fn($d) => \Illuminate\Support\Str::of($d)
                                                               ->ucfirst()
                                                               ->substr(0, 3),
                                                       )
                                                       ->join(' • ');

                                                   $start = \Carbon\Carbon::parse(
                                                       $course->schedule->start_time,
                                                   )->format('g:i');
                                                   $end = \Carbon\Carbon::parse($course->schedule->end_time)->format(
                                                       'g:i A',
                                                   );
                                                   $shift = ucfirst($course->schedule->shift);
                                               @endphp
                                               <div>
                                                   <div class="fw-semibold">{{ $days }}</div>
                                                   <div class="text-muted small">
                                                       <span class="badge bg-light text-dark">{{ $shift }}</span>
                                                       {{ $start }} – {{ $end }}
                                                   </div>
                                               </div>
                                           </span>
                                       </div>
                                       <div class="d-flex justify-content-between border-bottom py-2 ">
                                           <span>
                                               Status
                                           </span>
                                           <span class="text-dark">
                                               {{ ucfirst($course->status) }}
                                           </span>
                                       </div>
                                       {{-- students amount --}}
                                       <div class="d-flex justify-content-between border-bottom  py-2 ">
                                           <span>
                                               Students
                                           </span>
                                           <span class="text-dark">
                                               {{ $course->enrollments_count }}
                                           </span>
                                       </div>
                                       <div class="d-flex justify-content-between border-bottom py-2 ">
                                           <span>
                                               Price
                                           </span>
                                           <span class="text-dark">
                                               ${{ $course->price }}
                                           </span>
                                       </div>
                                       {{-- earning --}}
                                       <div class="d-flex justify-content-between pt-2 ">
                                           <span>
                                               Revenue
                                           </span>
                                           <span class="text-dark">
                                               ${{ $course->payments->sum('amount') }}
                                           </span>
                                       </div>
                                   </div>
                                   <!-- Card footer -->
                                   <div class="card-footer">
                                       <div class="row align-items-center g-0">
                                           <div class="col-auto">
                                               <img src="{{ $course->instructor->image == 'no-img.jpg' ? '/default-images/user/both.jpg' : $course->instructor->image }}"
                                                   class="rounded-circle avatar-xs" alt="avatar">
                                           </div>
                                           <div class="col ms-2">
                                               <span>
                                                   {{ $course->instructor ? $course->instructor->name : 'N/A' }}
                                               </span>
                                           </div>
                                           <div class="col-auto">
                                               <a href="javascript:void;" class="btn btn-sm btn-outline-secondary">
                                                   <i class="fe fe-phone"></i>
                                               </a>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                           </div>
                       @empty
                       @endforelse

                       <div class="col-lg-12 col-md-12 col-12">
                           <div class="pt-4">
                               <!-- Pagination -->
                               <nav>
                                   <ul class="pagination justify-content-center mb-0">
                                       <li class="page-item disabled">
                                           <a class="page-link mx-1 rounded" href="#">
                                               <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                                                   fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                                                   <path fill-rule="evenodd"
                                                       d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z">
                                                   </path>
                                               </svg>
                                           </a>
                                       </li>
                                       <li class="page-item active">
                                           <a class="page-link mx-1 rounded" href="#">1</a>
                                       </li>
                                       <li class="page-item">
                                           <a class="page-link mx-1 rounded" href="#">2</a>
                                       </li>
                                       <li class="page-item">
                                           <a class="page-link mx-1 rounded" href="#">3</a>
                                       </li>
                                       <li class="page-item">
                                           <a class="page-link mx-1 rounded" href="#">
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
               </div>
               <!-- tab pane -->
               <div class="tab-pane fade" id="tabPaneList" role="tabpanel" aria-labelledby="tabPaneList">
                   <!-- card -->
                   <div class="card">
                       <!-- card header -->
                       <div class="card-header">
                           <input type="search" class="form-control" placeholder="Search Course">
                       </div>
                       <!-- table -->
                       <div class="table-responsive">
                           <table class="table mb-0 text-nowrap table-hover table-centered">
                               <thead class="table-light">
                                   <tr>
                                       <th>Name</th>
                                       <th>Topic</th>
                                       <th>Courses</th>
                                       <th>Joined</th>
                                       <th>Students</th>
                                       <th>Rating</th>

                                       <th>Action</th>
                                   </tr>
                               </thead>
                               <tbody>
                                   <tr>
                                       <td>
                                           <div class="d-flex align-items-center">
                                               <img src="../../assets/images/avatar/avatar-12.jpg" alt=""
                                                   class="rounded-circle avatar-md me-2">
                                               <h5 class="mb-0">Guy Hawkins</h5>
                                           </div>
                                       </td>
                                       <td>Engineering Architect</td>
                                       <td>6 Courses</td>
                                       <td>7 July, 2020</td>
                                       <td>50,274</td>
                                       <td>
                                           4.5
                                           <span class="fs-6 align-top">
                                               <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11"
                                                   fill="currentColor" class="bi bi-star-fill text-secondary"
                                                   viewBox="0 0 16 16">
                                                   <path
                                                       d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                   </path>
                                               </svg>
                                           </span>
                                       </td>

                                       <td>
                                           <div class="hstack gap-4">
                                               <a href="#" data-bs-toggle="tooltip" data-placement="top"
                                                   aria-label="Message" data-bs-original-title="Message"><i
                                                       class="fe fe-mail"></i></a>
                                               <a href="#" data-bs-toggle="tooltip" data-placement="top"
                                                   aria-label="Delete" data-bs-original-title="Delete"><i
                                                       class="fe fe-trash"></i></a>
                                               <span class="dropdown dropstart">
                                                   <a class="btn-icon btn btn-ghost btn-sm rounded-circle"
                                                       href="#" role="button" data-bs-toggle="dropdown"
                                                       data-bs-offset="-20,20" aria-expanded="false">
                                                       <i class="fe fe-more-vertical"></i>
                                                   </a>
                                                   <span class="dropdown-menu">
                                                       <span class="dropdown-header">Settings</span>
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
                                           </div>
                                       </td>
                                   </tr>
                                   <tr>
                                       <td>
                                           <div class="d-flex align-items-center">
                                               <img src="../../assets/images/avatar/avatar-13.jpg" alt=""
                                                   class="rounded-circle avatar-md me-2">
                                               <h5 class="mb-0">Dianna Smiley</h5>
                                           </div>
                                       </td>
                                       <td>Front End Developer</td>
                                       <td>3 Courses</td>
                                       <td>6 July, 2020</td>
                                       <td>26,060</td>
                                       <td>
                                           4.5
                                           <span class="fs-6 align-top">
                                               <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11"
                                                   fill="currentColor" class="bi bi-star-fill text-secondary"
                                                   viewBox="0 0 16 16">
                                                   <path
                                                       d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                   </path>
                                               </svg>
                                           </span>
                                       </td>

                                       <td>
                                           <div class="hstack gap-4">
                                               <a href="#" data-bs-toggle="tooltip" data-placement="top"
                                                   aria-label="Message" data-bs-original-title="Message"><i
                                                       class="fe fe-mail"></i></a>
                                               <a href="#" data-bs-toggle="tooltip" data-placement="top"
                                                   aria-label="Delete" data-bs-original-title="Delete"><i
                                                       class="fe fe-trash"></i></a>
                                               <span class="dropdown dropstart">
                                                   <a class="btn-icon btn btn-ghost btn-sm rounded-circle"
                                                       href="#" role="button" data-bs-toggle="dropdown"
                                                       data-bs-offset="-20,20" aria-expanded="false">
                                                       <i class="fe fe-more-vertical"></i>
                                                   </a>
                                                   <span class="dropdown-menu">
                                                       <span class="dropdown-header">Settings</span>
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
                                           </div>
                                       </td>
                                   </tr>
                                   <tr>
                                       <td>
                                           <div class="d-flex align-items-center">
                                               <img src="../../assets/images/avatar/avatar-17.jpg" alt=""
                                                   class="rounded-circle avatar-md me-2">
                                               <h5 class="mb-0">Nia Sikhone</h5>
                                           </div>
                                       </td>
                                       <td>Web Developer, Designer, and Teacher</td>
                                       <td>12 Courses</td>
                                       <td>12 June, 2020</td>
                                       <td>8,234</td>
                                       <td>
                                           4.5
                                           <span class="fs-6 align-top">
                                               <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11"
                                                   fill="currentColor" class="bi bi-star-fill text-secondary"
                                                   viewBox="0 0 16 16">
                                                   <path
                                                       d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                   </path>
                                               </svg>
                                           </span>
                                       </td>

                                       <td>
                                           <div class="hstack gap-4">
                                               <a href="#" data-bs-toggle="tooltip" data-placement="top"
                                                   aria-label="Message" data-bs-original-title="Message"><i
                                                       class="fe fe-mail"></i></a>
                                               <a href="#" data-bs-toggle="tooltip" data-placement="top"
                                                   aria-label="Delete" data-bs-original-title="Delete"><i
                                                       class="fe fe-trash"></i></a>
                                               <span class="dropdown dropstart">
                                                   <a class="btn-icon btn btn-ghost btn-sm rounded-circle"
                                                       href="#" role="button" data-bs-toggle="dropdown"
                                                       data-bs-offset="-20,20" aria-expanded="false">
                                                       <i class="fe fe-more-vertical"></i>
                                                   </a>
                                                   <span class="dropdown-menu">
                                                       <span class="dropdown-header">Settings</span>
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
                                           </div>
                                       </td>
                                   </tr>
                                   <tr>
                                       <td>
                                           <div class="d-flex align-items-center">
                                               <img src="../../assets/images/avatar/avatar-14.jpg" alt=""
                                                   class="rounded-circle avatar-md me-2">
                                               <h5 class="mb-0">Jacob Jones</h5>
                                           </div>
                                       </td>
                                       <td>Bootstrap Expert</td>
                                       <td>7 Courses</td>
                                       <td>2 July, 2020</td>
                                       <td>14,944</td>
                                       <td>
                                           4.5
                                           <span class="fs-6 align-top">
                                               <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11"
                                                   fill="currentColor" class="bi bi-star-fill text-secondary"
                                                   viewBox="0 0 16 16">
                                                   <path
                                                       d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                   </path>
                                               </svg>
                                           </span>
                                       </td>

                                       <td>
                                           <div class="hstack gap-4">
                                               <a href="#" data-bs-toggle="tooltip" data-placement="top"
                                                   aria-label="Message" data-bs-original-title="Message"><i
                                                       class="fe fe-mail"></i></a>
                                               <a href="#" data-bs-toggle="tooltip" data-placement="top"
                                                   aria-label="Delete" data-bs-original-title="Delete"><i
                                                       class="fe fe-trash"></i></a>
                                               <span class="dropdown dropstart">
                                                   <a class="btn-icon btn btn-ghost btn-sm rounded-circle"
                                                       href="#" role="button" data-bs-toggle="dropdown"
                                                       data-bs-offset="-20,20" aria-expanded="false">
                                                       <i class="fe fe-more-vertical"></i>
                                                   </a>
                                                   <span class="dropdown-menu">
                                                       <span class="dropdown-header">Settings</span>
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
                                           </div>
                                       </td>
                                   </tr>
                                   <tr>
                                       <td>
                                           <div class="d-flex align-items-center">
                                               <img src="../../assets/images/avatar/avatar-15.jpg" alt=""
                                                   class="rounded-circle avatar-md me-2">
                                               <h5 class="mb-0">Kristin Watson</h5>
                                           </div>
                                       </td>
                                       <td>Web Development</td>
                                       <td>5 Courses</td>
                                       <td>1 July, 2020</td>
                                       <td>6,845</td>
                                       <td>
                                           4.5
                                           <span class="fs-6 align-top">
                                               <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11"
                                                   fill="currentColor" class="bi bi-star-fill text-secondary"
                                                   viewBox="0 0 16 16">
                                                   <path
                                                       d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                   </path>
                                               </svg>
                                           </span>
                                       </td>

                                       <td>
                                           <div class="hstack gap-4">
                                               <a href="#" data-bs-toggle="tooltip" data-placement="top"
                                                   aria-label="Message" data-bs-original-title="Message"><i
                                                       class="fe fe-mail"></i></a>
                                               <a href="#" data-bs-toggle="tooltip" data-placement="top"
                                                   aria-label="Delete" data-bs-original-title="Delete"><i
                                                       class="fe fe-trash"></i></a>
                                               <span class="dropdown dropstart">
                                                   <a class="btn-icon btn btn-ghost btn-sm rounded-circle"
                                                       href="#" role="button" data-bs-toggle="dropdown"
                                                       data-bs-offset="-20,20" aria-expanded="false">
                                                       <i class="fe fe-more-vertical"></i>
                                                   </a>
                                                   <span class="dropdown-menu">
                                                       <span class="dropdown-header">Settings</span>
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
                                           </div>
                                       </td>
                                   </tr>
                                   <tr>
                                       <td>
                                           <div class="d-flex align-items-center">
                                               <img src="../../assets/images/avatar/avatar-17.jpg" alt=""
                                                   class="rounded-circle avatar-md me-2">
                                               <h5 class="mb-0">Nia Sikhone</h5>
                                           </div>
                                       </td>
                                       <td>Web Developer, Designer, and Teacher</td>
                                       <td>12 Courses</td>
                                       <td>12 June, 2020</td>
                                       <td>8,234</td>
                                       <td>
                                           4.5
                                           <span class="fs-6 align-top">
                                               <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11"
                                                   fill="currentColor" class="bi bi-star-fill text-secondary"
                                                   viewBox="0 0 16 16">
                                                   <path
                                                       d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                   </path>
                                               </svg>
                                           </span>
                                       </td>

                                       <td>
                                           <div class="hstack gap-4">
                                               <a href="#" data-bs-toggle="tooltip" data-placement="top"
                                                   aria-label="Message" data-bs-original-title="Message"><i
                                                       class="fe fe-mail"></i></a>
                                               <a href="#" data-bs-toggle="tooltip" data-placement="top"
                                                   aria-label="Delete" data-bs-original-title="Delete"><i
                                                       class="fe fe-trash"></i></a>
                                               <span class="dropdown dropstart">
                                                   <a class="btn-icon btn btn-ghost btn-sm rounded-circle"
                                                       href="#" role="button" data-bs-toggle="dropdown"
                                                       data-bs-offset="-20,20" aria-expanded="false">
                                                       <i class="fe fe-more-vertical"></i>
                                                   </a>
                                                   <span class="dropdown-menu">
                                                       <span class="dropdown-header">Settings</span>
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
                                           </div>
                                       </td>
                                   </tr>
                                   <tr>
                                       <td>
                                           <div class="d-flex align-items-center">
                                               <img src="../../assets/images/avatar/avatar-16.jpg" alt=""
                                                   class="rounded-circle avatar-md me-2">
                                               <h5 class="mb-0">Rivao Luke</h5>
                                           </div>
                                       </td>
                                       <td>Web Development</td>
                                       <td>5 Courses</td>
                                       <td>1 July, 2020</td>
                                       <td>6,845</td>
                                       <td>
                                           4.5
                                           <span class="fs-6 align-top">
                                               <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11"
                                                   fill="currentColor" class="bi bi-star-fill text-secondary"
                                                   viewBox="0 0 16 16">
                                                   <path
                                                       d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                   </path>
                                               </svg>
                                           </span>
                                       </td>

                                       <td>
                                           <div class="hstack gap-4">
                                               <a href="#" data-bs-toggle="tooltip" data-placement="top"
                                                   aria-label="Message" data-bs-original-title="Message"><i
                                                       class="fe fe-mail"></i></a>
                                               <a href="#" data-bs-toggle="tooltip" data-placement="top"
                                                   aria-label="Delete" data-bs-original-title="Delete"><i
                                                       class="fe fe-trash"></i></a>
                                               <span class="dropdown dropstart">
                                                   <a class="btn-icon btn btn-ghost btn-sm rounded-circle"
                                                       href="#" role="button" data-bs-toggle="dropdown"
                                                       data-bs-offset="-20,20" aria-expanded="false">
                                                       <i class="fe fe-more-vertical"></i>
                                                   </a>
                                                   <span class="dropdown-menu">
                                                       <span class="dropdown-header">Settings</span>
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
                                           </div>
                                       </td>
                                   </tr>
                                   <tr>
                                       <td>
                                           <div class="d-flex align-items-center">
                                               <img src="../../assets/images/avatar/avatar-17.jpg" alt=""
                                                   class="rounded-circle avatar-md me-2">
                                               <h5 class="mb-0">Nia Sikhone</h5>
                                           </div>
                                       </td>
                                       <td>Web Developer, Designer, and Teacher</td>
                                       <td>12 Courses</td>
                                       <td>12 June, 2020</td>
                                       <td>8,234</td>
                                       <td>
                                           4.5
                                           <span class="fs-6 align-top">
                                               <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11"
                                                   fill="currentColor" class="bi bi-star-fill text-secondary"
                                                   viewBox="0 0 16 16">
                                                   <path
                                                       d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                   </path>
                                               </svg>
                                           </span>
                                       </td>

                                       <td>
                                           <div class="hstack gap-4">
                                               <a href="#" data-bs-toggle="tooltip" data-placement="top"
                                                   aria-label="Message" data-bs-original-title="Message"><i
                                                       class="fe fe-mail"></i></a>
                                               <a href="#" data-bs-toggle="tooltip" data-placement="top"
                                                   aria-label="Delete" data-bs-original-title="Delete"><i
                                                       class="fe fe-trash"></i></a>
                                               <span class="dropdown dropstart">
                                                   <a class="btn-icon btn btn-ghost btn-sm rounded-circle"
                                                       href="#" role="button" data-bs-toggle="dropdown"
                                                       data-bs-offset="-20,20" aria-expanded="false">
                                                       <i class="fe fe-more-vertical"></i>
                                                   </a>
                                                   <span class="dropdown-menu">
                                                       <span class="dropdown-header">Settings</span>
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
                                           </div>
                                       </td>
                                   </tr>
                                   <tr>
                                       <td>
                                           <div class="d-flex align-items-center">
                                               <img src="../../assets/images/avatar/avatar-18.jpg" alt=""
                                                   class="rounded-circle avatar-md me-2">

                                               <h5 class="mb-0">Xiaon Merry</h5>
                                           </div>
                                       </td>
                                       <td>Web Developer, Designer, and Teacher</td>
                                       <td>9 Courses</td>
                                       <td>8 June, 2020</td>
                                       <td>3,242</td>
                                       <td>
                                           4.5
                                           <span class="fs-6 align-top">
                                               <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11"
                                                   fill="currentColor" class="bi bi-star-fill text-secondary"
                                                   viewBox="0 0 16 16">
                                                   <path
                                                       d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z">
                                                   </path>
                                               </svg>
                                           </span>
                                       </td>

                                       <td>
                                           <div class="hstack gap-4">
                                               <a href="#" data-bs-toggle="tooltip" data-placement="top"
                                                   aria-label="Message" data-bs-original-title="Message"><i
                                                       class="fe fe-mail"></i></a>
                                               <a href="#" data-bs-toggle="tooltip" data-placement="top"
                                                   aria-label="Delete" data-bs-original-title="Delete"><i
                                                       class="fe fe-trash"></i></a>
                                               <span class="dropdown dropstart">
                                                   <a class="btn-icon btn btn-ghost btn-sm rounded-circle"
                                                       href="#" role="button" data-bs-toggle="dropdown"
                                                       data-bs-offset="-20,20" aria-expanded="false">
                                                       <i class="fe fe-more-vertical"></i>
                                                   </a>
                                                   <span class="dropdown-menu">
                                                       <span class="dropdown-header">Settings</span>
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
                                           </div>
                                       </td>
                                   </tr>
                               </tbody>
                           </table>
                           <!-- Pagination -->
                           <div class="card-footer">
                               <nav>
                                   <ul class="pagination justify-content-center mb-0">
                                       <li class="page-item disabled">
                                           <a class="page-link mx-1 rounded" href="#">
                                               <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                                                   fill="currentColor" class="bi bi-chevron-left"
                                                   viewBox="0 0 16 16">
                                                   <path fill-rule="evenodd"
                                                       d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z">
                                                   </path>
                                               </svg>
                                           </a>
                                       </li>
                                       <li class="page-item active">
                                           <a class="page-link mx-1 rounded" href="#">1</a>
                                       </li>
                                       <li class="page-item">
                                           <a class="page-link mx-1 rounded" href="#">2</a>
                                       </li>
                                       <li class="page-item">
                                           <a class="page-link mx-1 rounded" href="#">3</a>
                                       </li>
                                       <li class="page-item">
                                           <a class="page-link mx-1 rounded" href="#">
                                               <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                                                   fill="currentColor" class="bi bi-chevron-right"
                                                   viewBox="0 0 16 16">
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
               </div>
           </div>
       </div>
   </div>
