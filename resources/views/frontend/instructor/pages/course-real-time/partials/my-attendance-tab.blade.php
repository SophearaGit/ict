<div class="tab-pane fade" id="attendance" role="tabpanel" aria-labelledby="attendance-tab">

    <div class="sheet">

        <!-- Header -->
        <div class="top-header">
            <div class="logo">
                <i class="fe fe-calendar text-primary fs-1 me-2"></i>
            </div>
            <div>
                <div class="title">ICT Professional Training Center</div>
                <div class="sub-title">Teacher's Attendant</div>
            </div>
        </div>
        @if (request('from_date') && request('to_date'))
            <div class="alert alert-primary d-flex justify-content-between align-items-center mb-3">
                <div>
                    <strong>Filtered:</strong>
                    {{ request('from_date') }} → {{ request('to_date') }}
                </div>

                <div class="d-flex gap-4">
                    <span><strong>Hours:</strong> {{ $course->filtered_hours ?? 0 }}</span>
                    <span><strong>Sessions:</strong>
                        {{ $course->filtered_sessions ?? 0 }}</span>
                    <span><strong>Earnings:</strong>
                        ${{ $course->filtered_earnings ?? 0 }}</span>
                </div>
            </div>
        @endif
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

        <table id="attendanceTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Date</th>
                    <th>Time in</th>
                    <th>Time out</th>
                    <th>T H</th>
                    <th>A T H</th>
                    {{-- <th>Room</th> --}}
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
                                    value="{{ $attendance->date }}" class="form-control text-dark" readonly>
                            </td>
                            <td>
                                <input type="time" name="attendances[{{ $index }}][start_time]"
                                    value="{{ \Carbon\Carbon::parse($attendance->start_time)->format('H:i') }}"
                                    class="form-control text-dark" readonly>
                            </td>
                            <td>
                                <input type="time" name="attendances[{{ $index }}][end_time]"
                                    value="{{ \Carbon\Carbon::parse($attendance->end_time)->format('H:i') }}"
                                    class="form-control text-dark" readonly>
                            </td>
                            <td>
                                <input type="text" name="attendances[{{ $index }}][total_hours]"
                                    value="{{ number_format($attendance->total_hours) }}"
                                    class="form-control total-hours text-uppercase text-dark text-center" readonly>
                            </td>
                            <td>
                                <input type="text" name="attendances[{{ $index }}][actual_hours]"
                                    value="{{ number_format($attendance->actual_hours) }}"
                                    class="form-control actual-hours text-uppercase text-dark text-center" readonly>
                            </td>
                            {{-- <td>
                                <input type="text"
                                    name="attendances[{{ $index }}][room]"
                                    value="{{ $attendance->room }}"
                                    class="form-control text-uppercase text-dark text-center">
                            </td> --}}
                            <td>
                                <input type="number" name="attendances[{{ $index }}][late_minutes]"
                                    value="{{ $attendance->late_minutes }}" class="form-control text-center" readonly>
                            </td>
                            <td>
                                <input type="text" name="attendances[{{ $index }}][late_reason]"
                                    value="{{ $attendance->late_reason }}" class="form-control" readonly>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
