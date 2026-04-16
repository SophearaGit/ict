<div class="tab-pane fade " id="attendance" role="tabpanel" aria-labelledby="attendance-tab">
    <div class="col-lg-12 col-md-9 col-12">
        <!-- Card -->
        <div class="card mb-4">
            <!-- Card body -->
            <div class="p-4 d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0">My Attendance</h3>
                    <span>Track your attendance for this course.</span>
                </div>
            </div>
        </div>
        <div class="tab-content">
            <div class="tab-pane fade pb-4 active show" id="" role="tabpanel" aria-labelledby="tabPaneGrid">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-12 mb-3">
                        <div class="row">
                            <div class="col-12">
                                <div class="sheet">

                                    <!-- Header -->
                                    <div class="top-header">
                                        <div class="logo">
                                            {{-- logo image --}}
                                            ICT
                                        </div>
                                        <div>
                                            <div class="title">ICT Professional
                                                Training
                                                Center</div>
                                            <div class="sub-title">Teacher's Attendant
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Teacher -->
                                    <div class="info-row">
                                        <div class="info-label">
                                            Teacher's Name:
                                        </div>
                                        <div class="info-value text-capitalize text-center" contenteditable="false">
                                            <strong class="text-black">
                                                {{ $course->instructor->name ?? 'No Instructor' }}
                                            </strong>
                                        </div>
                                    </div>
                                    <!-- Subject -->
                                    <div class="info-row highlight">
                                        <div class="info-label">Subject:</div>
                                        <div class="info-value text-capitalize text-center" contenteditable="false">
                                            <strong class="text-black">
                                                {{ $course->title }} |
                                                @if ($course->schedule)
                                                    @php
                                                        $days = collect(explode('-', $course->schedule->study_day))
                                                            ->map(fn($day) => ucfirst($day))
                                                            ->implode(' • ');
                                                        $start = \Carbon\Carbon::parse(
                                                            $course->schedule->start_time,
                                                        )->format('g:i ');
                                                        $end = \Carbon\Carbon::parse(
                                                            $course->schedule->end_time,
                                                        )->format('g:i A');
                                                        $shift = ucfirst($course->schedule->shift);
                                                    @endphp
                                                    <strong>
                                                        {{ $days }} |
                                                        {{ $shift }} (
                                                        {{ $start }}
                                                        –
                                                        {{ $end }} )
                                                    </strong>
                                                @else
                                                    <span class="text-muted">No
                                                        schedule</span>
                                                @endif
                                            </strong>
                                        </div>
                                    </div>


                                    <div class="table-responsive">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Date</th>
                                                    <th>Time In</th>
                                                    <th>Time Out</th>
                                                    <th>T.H</th>
                                                    <th>A.T.H</th>
                                                    <th>Room</th>
                                                    <th>Late (min)</th>
                                                    <th>Note</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($course->teacherAttendances as $index => $attendance)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $attendance->formatted_date }}
                                                        </td>
                                                        <td>{{ $attendance->formatted_start_time }}
                                                        </td>
                                                        <td>{{ $attendance->formatted_end_time }}
                                                        </td>
                                                        <td>{{ $attendance->formatted_total_hours }}
                                                        </td>
                                                        <td>{{ $attendance->formatted_actual_hours }}
                                                        </td>
                                                        <td class="text-uppercase">
                                                            {{ $attendance->formatted_room }}
                                                        </td>
                                                        <td>{{ $attendance->formatted_late }}
                                                        </td>
                                                        <td>{{ $attendance->formatted_note }}
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="9" class="text-muted">
                                                            No attendance records
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

                </div>
            </div>
        </div>
    </div>
</div>
