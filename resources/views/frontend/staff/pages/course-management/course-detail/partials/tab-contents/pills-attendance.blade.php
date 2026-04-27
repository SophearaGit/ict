<div class="tab-pane fade" id="pills-attendance" role="tabpanel" aria-labelledby="pills-attendance-tab"
    tabindex="0">
    <div class="row">
        <div class="col-12">
            <div class="sheet">

                <!-- Header -->
                <div class="top-header">
                    <div class="logo">
                        <i class="ti ti-calendar-check fs-12 me-2"></i>
                    </div>
                    <div>
                        <div class="title">ICT Professional Training Center</div>
                        <div class="sub-title">Teacher's Attendant</div>
                    </div>
                    {{-- <form method="GET" class="row g-2 mb-3">

                                <div class="col-md-6">
                                    <label class="mb-1">Filter by Date Range</label>
                                    <div class="input-daterange input-group" id="date-range">

                                        <input type="text" class="form-control" name="from_date"
                                            placeholder="From date" value="{{ request('from_date') }}"
                                            id="datepicker-autoclose">

                                        <span class="input-group-text bg-info text-white">TO</span>

                                        <input type="text" class="form-control" name="to_date" placeholder="To date"
                                            value="{{ request('to_date') }}">
                                    </div>
                                </div>

                                <div class="col-md-3 d-flex align-items-end">
                                    <button class="btn btn-primary w-100">
                                        <i class="ti ti-search"></i> Filter
                                    </button>
                                </div>

                                <div class="col-md-3 d-flex align-items-end">
                                    <a href="{{ route('staff.courses.show', $course->id) }}"
                                        class="btn btn-secondary w-100">
                                        Reset
                                    </a>
                                </div>
                            </form> --}}
                </div>
                <form method="GET" class="mb-3">
                    <div class="filter-bar p-3 rounded-3 bg-light border">

                        <div class="row g-2 align-items-end">

                            <!-- Date Range -->
                            <div class="col-12 col-md-6 col-lg-7">
                                <label class="form-label fw-semibold mb-1">
                                    <i class="ti ti-calendar me-1"></i> Date Range
                                </label>

                                <div class="input-daterange input-group" id="date-range">
                                    <input type="text" class="form-control" name="from_date" placeholder="From"
                                        value="{{ request('from_date', now()->startOfMonth()->format('Y-m-d')) }}">

                                    <span class="input-group-text bg-primary text-white px-2 px-md-3">
                                        TO
                                    </span>

                                    <input type="text" class="form-control" name="to_date" placeholder="To"
                                        value="{{ request('to_date', now()->endOfMonth()->format('Y-m-d')) }}">
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="col-12 col-md-6 col-lg-5">
                                <div class="d-flex flex-column flex-md-row gap-2">

                                    <button class="btn btn-primary w-100">
                                        <i class="ti ti-search me-1"></i> Filter
                                    </button>

                                    <a href="{{ route('staff.courses.show', $course->id) }}"
                                        class="btn btn-outline-secondary w-100">
                                        Reset
                                    </a>

                                </div>
                            </div>

                        </div>

                    </div>
                </form>
                {{-- @if (request('from_date') && request('to_date'))
                            <div class="alert alert-primary d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <strong>Filtered:</strong>
                                    {{ request('from_date') }} → {{ request('to_date') }}
                                </div>

                                <div class="d-flex gap-4">
                                    <span><strong>Hours:</strong> {{ $course->filtered_hours ?? 0 }}</span>
                                    <span><strong>Sessions:</strong> {{ $course->filtered_sessions ?? 0 }}</span>
                                    <span><strong>Earnings:</strong> ${{ $course->filtered_earnings ?? 0 }}</span>
                                </div>
                            </div>
                        @endif --}}
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
                                    $start = \Carbon\Carbon::parse($course->schedule->start_time)->format('g:i ');
                                    $end = \Carbon\Carbon::parse($course->schedule->end_time)->format('g:i A');
                                    $shift = ucfirst($course->schedule->shift);
                                @endphp
                                <strong>
                                    {{ $days }} | {{ $shift }} (
                                    {{ $start }}
                                    –
                                    {{ $end }} )
                                </strong>
                            @else
                                <span class="text-muted">No schedule</span>
                            @endif
                        </strong>
                    </div>
                </div>
                <!-- Table -->
                <form action="{{ route('staff.teacher.attendance.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                    <input type="hidden" name="teacher_id" value="{{ $course->instructor->id ?? '' }}">
                    <input type="hidden" name="schedule_id" value="{{ $course->schedule->id ?? '' }}">
                    <table id="attendanceTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Date</th>
                                <th>Time in</th>
                                <th>Time out</th>
                                <th>T H</th>
                                <th>A T H</th>
                                <th>Room</th>
                                <th>
                                    នាទីខ្វះ
                                </th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody id="attendanceBody">
                            @php
                                $attendances = $course->teacherAttendances;
                            @endphp

                            @if ($attendances->isNotEmpty())
                                @foreach ($attendances as $index => $attendance)
                                    <tr>
                                        <td style="padding: 10px">{{ $index + 1 }}</td>

                                        <td style="display:none;">
                                            <input type="hidden" name="attendances[{{ $index }}][id]"
                                                value="{{ $attendance->id }}">
                                        </td>

                                        <td>
                                            <input type="date" name="attendances[{{ $index }}][date]"
                                                value="{{ $attendance->date }}" class="form-control text-dark"
                                                readonly>
                                        </td>

                                        <td>
                                            <input type="time" name="attendances[{{ $index }}][start_time]"
                                                value="{{ \Carbon\Carbon::parse($attendance->start_time)->format('H:i') }}"
                                                class="form-control text-dark">
                                        </td>

                                        <td>
                                            <input type="time" name="attendances[{{ $index }}][end_time]"
                                                value="{{ \Carbon\Carbon::parse($attendance->end_time)->format('H:i') }}"
                                                class="form-control text-dark">
                                        </td>

                                        <td>
                                            <input type="text" name="attendances[{{ $index }}][total_hours]"
                                                value="{{ number_format($attendance->total_hours) }}"
                                                class="form-control total-hours text-uppercase text-dark text-center"
                                                readonly>
                                        </td>

                                        <td>
                                            <input type="text" name="attendances[{{ $index }}][actual_hours]"
                                                value="{{ number_format($attendance->actual_hours) }}"
                                                class="form-control actual-hours text-uppercase text-dark text-center"
                                                readonly>
                                        </td>

                                        <td>
                                            <input type="text" name="attendances[{{ $index }}][room]"
                                                value="{{ $attendance->room }}"
                                                class="form-control text-uppercase text-dark text-center">
                                        </td>

                                        <td>
                                            <input type="number" name="attendances[{{ $index }}][late_minutes]"
                                                value="{{ $attendance->late_minutes }}"
                                                class="form-control text-center">
                                        </td>
                                        <td>
                                            <input type="text" name="attendances[{{ $index }}][late_reason]"
                                                value="{{ $attendance->late_reason }}" class="form-control">
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            @php
                                $nextIndex = $attendances->count();
                            @endphp

                            <tr>
                                <td style="padding: 10px">{{ $nextIndex + 1 }}</td>

                                <td style="display:none;">
                                    <input type="hidden" name="attendances[{{ $nextIndex }}][id]"
                                        value="">
                                </td>

                                <td>
                                    <input type="date" name="attendances[{{ $nextIndex }}][date]"
                                        class="form-control" value="{{ now()->format('Y-m-d') }}" readonly>
                                </td>

                                <td>
                                    <input type="time" name="attendances[{{ $nextIndex }}][start_time]"
                                        class="form-control">
                                </td>

                                <td>
                                    <input type="time" name="attendances[{{ $nextIndex }}][end_time]"
                                        class="form-control">
                                </td>

                                <td>
                                    <input type="text" name="attendances[{{ $nextIndex }}][total_hours]"
                                        class="form-control total-hours" readonly>
                                </td>

                                <td>
                                    <input type="text" name="attendances[{{ $nextIndex }}][actual_hours]"
                                        class="form-control actual-hours" readonly>
                                </td>

                                <td>
                                    <input type="text" name="attendances[{{ $nextIndex }}][room]"
                                        class="form-control">
                                </td>

                                <td>
                                    <input type="number" name="attendances[{{ $nextIndex }}][late_minutes]"
                                        class="form-control">
                                </td>

                                <td>
                                    <input type="text" name="attendances[{{ $nextIndex }}][late_reason]"
                                        class="form-control">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-primary mt-3">
                        Save Attendance
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
