@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('content')
    @include('frontend.staff.pages.partials.breadcrumb')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-md-flex align-items-center mb-9">
                        <div>
                            <h5 class="card-title fw-semibold mb-2">
                                Schedules
                            </h5>
                            <p class="card-subtitle text-muted">
                                You can create new schedules from this page.
                            </p>
                        </div>
                        <div class="ms-auto mt-4 mt-md-0">
                            <a href="{{ route('staff.schedules.index') }}" class="btn btn-primary">
                                <i class="ti ti-arrow-back-up me-1"></i> Back
                            </a>
                        </div>
                    </div>


                    <form class="" action="{{ route('staff.schedules.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="form-floating mb-3 col-md-6">
                                <select class="form-select" name="study_day" id="study_day">
                                    <option value="" disabled selected>Select Study Day</option>
                                    <option value="mon-wed-fri">Mon-Wed-Fri</option>
                                    <option value="tue-thu">Tue-Thu</option>
                                    <option value="saturday">Saturday</option>
                                    <option value="sunday">Sunday</option>
                                </select>
                                <label><i class="ti ti-calendar-event me-2 fs-4 text-info"></i><span
                                        class="border-start border-info ps-3">Study Day</span></label>
                                <x-input-error :messages="$errors->get('study_day')" class="text-danger mt-2" />
                            </div>
                            <div class="form-floating mb-3 col-md-6">
                                <select class="form-select" name="shift" id="shift">
                                    <option value="" disabled selected>Select Shift</option>
                                    <option value="morning">Morning</option>
                                    <option value="afternoon">Afternoon</option>
                                    <option value="evening">Evening</option>
                                </select>
                                <label><i class="ti ti-sunrise me-2 fs-4 text-info"></i><span
                                        class="border-start border-info ps-3">Shift</span></label>
                                <x-input-error :messages="$errors->get('shift')" class="text-danger mt-2" />
                            </div>
                            {{-- <div class="form-floating mb-3 col-md-4">
                                <select class="form-select" name="room" id="room">
                                    <option value="" disabled selected>Select Room</option>
                                    <option value="a">A</option>
                                    <option value="b">B</option>
                                    <option value="c">C</option>
                                    <option value="d">D</option>
                                    <option value="e">E</option>
                                    <option value="f">F</option>
                                </select>
                                <label><i class="ti ti-building me-2 fs-4 text-info"></i><span
                                        class="border-start border-info ps-3">Room</span></label>
                                <x-input-error :messages="$errors->get('room')" class="text-danger mt-2" />
                            </div> --}}


                        </div>

                        {{-- <div class="row">
                            <div class="form-floating mb-3 col-md-6">
                                <input type="date" class="form-control " placeholder="Start Date" name="start_date">
                                <label><i class="ti ti-calendar me-2 fs-4 text-info"></i><span
                                        class="border-start border-info ps-3">Start Date</span></label>
                                <x-input-error :messages="$errors->get('start_date')" class="text-danger mt-2" />
                            </div>
                            <div class="form-floating mb-3 col-md-6">
                                <input type="date" class="form-control " placeholder="End Date" name="end_date">
                                <label><i class="ti ti-calendar me-2 fs-4 text-info"></i><span
                                        class="border-start border-info ps-3">End Date</span></label>
                                <x-input-error :messages="$errors->get('end_date')" class="text-danger mt-2" />
                            </div>
                        </div> --}}

                        <div class="row">
                            <div class="form-floating mb-3 col-md-6">
                                <input type="time" class="form-control " placeholder="Start Time" name="start_time">
                                <label><i class="ti ti-clock me-2 fs-4 text-info"></i><span
                                        class="border-start border-info ps-3">Start Time</span></label>
                                <x-input-error :messages="$errors->get('start_time')" class="text-danger mt-2" />
                            </div>
                            <div class="form-floating mb-3 col-md-6">
                                <input type="time" class="form-control " placeholder="End Time" name="end_time">
                                <label><i class="ti ti-clock me-2 fs-4 text-info"></i><span
                                        class="border-start border-info ps-3">End Time</span></label>
                                <x-input-error :messages="$errors->get('end_time')" class="text-danger mt-2" />
                            </div>
                        </div>

                        <div class="d-md-flex align-items-center">
                            <div class="mt-3 mt-md-0 ms-auto">
                                <button type="submit" class="btn btn-info font-medium rounded-pill px-4">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-send me-2 fs-4"></i>
                                        Submit
                                    </div>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
