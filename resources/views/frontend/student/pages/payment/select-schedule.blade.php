@extends('frontend.layouts.new.master')
@section('page_title', 'Select Schedule — ' . $course->title)
@push('styles')
    <link rel="stylesheet" href="/frontend/asset/css/checkout.css?v={{ @filemtime(public_path('frontend/asset/css/checkout.css')) }}">
@endpush
@section('content')

    @php
        // Only worth calling out "Recommended" when there's actually a
        // choice to make — with one section, every card would say it.
        $hasMultipleSections = $sections->count() > 1;
    @endphp

    <main class="checkout-wrap">

        <!-- Breadcrumb -->
        <nav class="checkout-crumb" aria-label="Breadcrumb" data-aos="fade-up">
            <a href="{{ route('course') }}">Courses</a>
            <i class="fa-solid fa-chevron-right"></i>
            <a href="{{ route('course.details', $course->slug) }}">{{ $course->title }}</a>
            <i class="fa-solid fa-chevron-right"></i>
            <span aria-current="page">Select Schedule</span>
        </nav>

        <!-- Stepper -->
        <ol class="checkout-stepper checkout-stepper--three" data-aos="fade-up">
            <li class="step is-active" id="step-schedule">
                <span class="step-dot">1</span>
                <span class="step-label">Select Schedule</span>
            </li>
            <li class="step-line"></li>
            <li class="step" id="step-payment">
                <span class="step-dot">2</span>
                <span class="step-label">Review &amp; Pay</span>
            </li>
            <li class="step-line"></li>
            <li class="step" id="step-success">
                <span class="step-dot">3</span>
                <span class="step-label">Confirmed</span>
            </li>
        </ol>

        <!-- Course header -->
        <section class="co-card schedule-select-header" data-aos="fade-up">
            <div class="course-summary-top" style="border-bottom:none;margin-bottom:0;padding-bottom:0;">
                <div class="course-thumb">
                    <img src="{{ $course->thumbnail ? asset($course->thumbnail) : asset('default-images/course-default.jpg') }}"
                        alt="{{ $course->title }} thumbnail">
                </div>
                <div class="course-summary-info">
                    <span class="course-badge">{{ $course->category->name ?? 'Course' }}</span>
                    <h2 class="course-title">{{ $course->title }}</h2>
                    <p class="schedule-select-sub">
                        Choose the batch that fits your availability — you'll review pricing and pay on the next
                        step.
                    </p>
                </div>
            </div>
        </section>

        <!-- Schedule options -->
        <div class="schedule-select-grid" data-aos="fade-up">
            @forelse ($sections as $section)
                @php
                    $schedule = $section->schedule;
                    $shiftLabel = $schedule?->shift_label;
                    $shiftKey = strtolower($shiftLabel ?? '');
                    $isDisabledCard = $section->is_full || $section->already_enrolled;
                @endphp
                <article
                    class="co-card schedule-option-card {{ $isDisabledCard ? 'is-disabled-card' : '' }} {{ $section->is_origin && $hasMultipleSections ? 'is-recommended' : '' }} {{ !$section->is_full ? 'is-selectable' : '' }}"
                    @if ($section->already_enrolled)
                        data-card-action="link"
                        data-card-href="{{ route('student.my.course.detail', $section->id) }}"
                    @elseif (! $section->is_full)
                        data-card-action="select"
                    @endif
                    @if (! $section->is_full)
                        role="button"
                        tabindex="0"
                        aria-label="{{ $section->already_enrolled ? 'Go to course' : 'Select this schedule' }}"
                    @endif
                >
                    <div class="schedule-badge-row">
                        @if ($shiftLabel)
                            <span class="schedule-badge schedule-badge--{{ in_array($shiftKey, ['morning', 'afternoon', 'evening']) ? $shiftKey : 'other' }}">
                                <i class="fa-regular fa-sun" aria-hidden="true"></i> {{ $shiftLabel }}
                            </span>
                        @endif

                        @if ($section->already_enrolled)
                            <span class="schedule-badge schedule-badge--enrolled">
                                <i class="fa-solid fa-circle-check" aria-hidden="true"></i> Already Enrolled
                            </span>
                        @elseif ($section->is_full)
                            <span class="schedule-badge schedule-badge--full">
                                <i class="fa-solid fa-ban" aria-hidden="true"></i> Class Full
                            </span>
                        @elseif ($section->is_origin && $hasMultipleSections)
                            <span class="schedule-badge schedule-badge--recommended">
                                <i class="fa-solid fa-star" aria-hidden="true"></i> Recommended
                            </span>
                        @endif
                    </div>

                    <h3 class="schedule-option-days">
                        {{ $schedule?->short_days ?? 'Schedule TBA' }}
                    </h3>
                    <p class="schedule-option-time">
                        <i class="fa-regular fa-clock" aria-hidden="true"></i>
                        {{ $schedule?->formatted_time ?? 'Time to be announced' }}
                    </p>

                    <div class="schedule-option-meta">
                        <div class="schedule-option-meta-item">
                            <i class="fa-solid fa-chalkboard-user" aria-hidden="true"></i>
                            <span>{{ $section->instructor->name ?? 'Instructor TBA' }}</span>
                        </div>
                        <div class="schedule-option-meta-item">
                            <i class="fa-regular fa-calendar-check" aria-hidden="true"></i>
                            <span>Starts {{ $section->start_date?->format('F j, Y') ?? 'TBA' }}</span>
                        </div>
                        <div class="schedule-option-meta-item">
                            <i class="fa-regular fa-hourglass-half" aria-hidden="true"></i>
                            <span>{{ $section->duration ?? 48 }} hours total</span>
                        </div>
                        @if ($schedule?->room)
                            <div class="schedule-option-meta-item">
                                <i class="fa-solid fa-door-open" aria-hidden="true"></i>
                                <span>Room {{ $schedule->room }}</span>
                            </div>
                        @endif
                    </div>

                    @if ($section->has_capacity)
                        @php
                            $seatsPct = $section->capacity > 0
                                ? min(100, round(($section->students_count / $section->capacity) * 100))
                                : 0;
                            $seatsState = $section->is_full ? 'full' : ($seatsPct >= 80 ? 'low' : 'ok');
                        @endphp
                        <div class="seats-row">
                            <div class="seats-bar">
                                <div class="seats-bar-fill seats-bar-fill--{{ $seatsState }}" style="width: {{ $seatsPct }}%"></div>
                            </div>
                            <span class="seats-label seats-label--{{ $seatsState }}">
                                @if ($section->is_full)
                                    Full — {{ $section->students_count }}/{{ $section->capacity }} seats taken
                                @else
                                    {{ $section->seats_left }} of {{ $section->capacity }} seats left
                                @endif
                            </span>
                        </div>
                    @endif

                    @if ($section->already_enrolled)
                        <div class="schedule-select-footer schedule-select-footer--enrolled">
                            <span><i class="fa-solid fa-arrow-right"></i> Go to Course</span>
                        </div>
                    @elseif ($section->is_full)
                        <div class="schedule-select-footer schedule-select-footer--full">
                            <span><i class="fa-solid fa-ban"></i> Class Full</span>
                        </div>
                    @else
                        <form class="schedule-select-form" action="{{ route('student.course.enroll', $section->id) }}"
                            method="POST">
                            @csrf
                        </form>
                        <div class="schedule-select-footer schedule-select-footer--select">
                            <span><i class="fa-solid fa-check"></i> Select This Schedule</span>
                            <i class="fa-solid fa-chevron-right schedule-select-chevron" aria-hidden="true"></i>
                        </div>
                    @endif
                </article>
            @empty
                <p class="no-schedule-note">No active sections are available for this course right now.</p>
            @endforelse
        </div>
    </main>

    <script>
        (function() {
            // The whole card is the click target now (not just a button at
            // the bottom). A card either submits its own hidden enroll form
            // (data-card-action="select") or navigates to the already-
            // enrolled course (data-card-action="link"). Cards with neither
            // attribute (full classes) are not interactive.
            //
            // Guards against a double-click (or picking a second card while
            // the first request is still in flight) firing two enroll
            // requests for the same student — each one creates/updates an
            // invoice server-side, so a duplicate click shouldn't quietly
            // double that work.
            var cards = document.querySelectorAll('.schedule-option-card[data-card-action]');
            var submitting = false;

            function setCardsBusy(activeCard) {
                submitting = true;
                cards.forEach(function(card) {
                    card.classList.add('is-busy');
                    card.setAttribute('aria-disabled', 'true');
                });
                var footer = activeCard.querySelector('.schedule-select-footer');
                if (footer) {
                    footer.classList.add('is-loading');
                    footer.innerHTML = '<span><span class="btn-spinner" aria-hidden="true"></span> ' +
                        (activeCard.dataset.cardAction === 'link' ? 'Loading…' : 'Enrolling…') + '</span>';
                }
            }

            function activateCard(card) {
                if (submitting) return;

                if (card.dataset.cardAction === 'link' && card.dataset.cardHref) {
                    setCardsBusy(card);
                    window.location.href = card.dataset.cardHref;
                    return;
                }

                if (card.dataset.cardAction === 'select') {
                    var form = card.querySelector('.schedule-select-form');
                    if (!form) return;
                    setCardsBusy(card);
                    if (form.requestSubmit) {
                        form.requestSubmit();
                    } else {
                        form.submit();
                    }
                }
            }

            cards.forEach(function(card) {
                card.addEventListener('click', function() {
                    activateCard(card);
                });
                card.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        activateCard(card);
                    }
                });
            });
        })();
    </script>
@endsection
