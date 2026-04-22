 <div class="tab-pane fade " id="students" role="tabpanel" aria-labelledby="students-tab">
     <div class="table-responsive border-0">
         <table class="table mb-0 text-nowrap table-hover table-centered">
             <thead class="table-light">
                 <tr>
                     <th>Name</th>
                     <th>Enrolled</th>
                     <th>Joined At</th>
                     <th>TotaL Payment</th>
                     <th>Locations</th>
                     {{-- <th>Action</th> --}}
                 </tr>
             </thead>
             <tbody>
                 @forelse ($course->enrollments as $enrollment)
                     <tr>
                         <td>
                             <div class="d-flex align-items-center">
                                 <div class="position-relative">
                                     <img src="{{ $enrollment->student->image === 'no-img.jpg' ? asset('/default-images/user/both.jpg') : $enrollment->student->image }}"
                                         alt="
                                                                        {{ $enrollment->student->name }}"
                                         class="rounded-circle avatar-md me-2">
                                     <a href="#" class="position-absolute mt-5 ms-n4">
                                         <span class="status bg-success"></span>
                                     </a>
                                 </div>
                                 <h5 class="mb-0">
                                     {{ $enrollment->student->name }}
                                 </h5>
                             </div>
                         </td>
                         <td>
                             {{-- how many course this user enroll --}}
                             {{ $enrollment->student->enrollments->count() }} courses
                         </td>
                         <td>
                             {{-- 7 July, 2020 --}}
                             {{ $enrollment->created_at->format('j F, Y') }}
                         </td>
                         <td>
                             {{-- $600 --}}
                             ${{ $enrollment->course->price }}
                         </td>
                         <td>
                             {{-- Dhaka, Bangladesh --}}
                             {{ $enrollment->student->location ?? 'N/A' }}
                         </td>

                         {{-- <td>
                                                            <div class="hstack gap-4">
                                                                <a href="#" class="fe fe-mail"
                                                                    data-bs-toggle="tooltip" data-placement="top"
                                                                    aria-label="Message"
                                                                    data-bs-original-title="Message"></a>
                                                                <a href="#" data-bs-toggle="tooltip"
                                                                    data-placement="top" aria-label="Delete"
                                                                    data-bs-original-title="Delete"><i
                                                                        class="fe fe-trash"></i></a>
                                                                <span class="dropdown dropstart">
                                                                    <a class="btn-icon btn btn-ghost btn-sm rounded-circle"
                                                                        href="#" role="button"
                                                                        data-bs-toggle="dropdown" data-bs-offset="-20,20"
                                                                        aria-expanded="false">
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
                                                        </td> --}}
                     </tr>
                 @empty
                     <tr>
                         <td colspan="6" class="text-center">
                             No students enrolled in this course.
                         </td>
                     </tr>
                 @endforelse
             </tbody>
         </table>
     </div>
 </div>
