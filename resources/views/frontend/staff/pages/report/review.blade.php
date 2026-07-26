@extends('frontend.staff.layout.master')
@section('page_title', $page_title)
@push('styles')
    <style>
        .search-field {
            position: relative;
            flex: 1 1 220px;
            min-width: 200px;
        }

        .search-field i {
            position: absolute;
            top: 50%;
            left: 1rem;
            transform: translateY(-50%);
            color: var(--bs-gray-400);
        }

        .search-field input {
            padding-left: 2.5rem;
        }

        .filter-chip {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            background: var(--bs-light-primary);
            color: var(--bs-primary);
            border-radius: 999px;
            padding: .3rem .4rem .3rem .8rem;
            font-size: .82rem;
            font-weight: 500;
        }

        .filter-chip a {
            color: var(--bs-primary);
            line-height: 1;
            display: inline-flex;
        }

        /* Legend / status toggle chips: double as a live filter for the calendar.
           Active = solid tint; inactive = greyed out and events hidden. */
        .status-legend {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            border: none;
            border-radius: 999px;
            padding: .4rem .9rem;
            font-size: .82rem;
            font-weight: 600;
            cursor: pointer;
            transition: opacity .15s ease, transform .1s ease;
        }

        .status-legend:active {
            transform: scale(0.97);
        }

        .status-legend .dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .status-legend.is-off {
            opacity: 0.35;
        }

        .status-legend.legend-pending {
            background: var(--bs-light-warning);
            color: var(--bs-warning);
        }

        .status-legend.legend-reviewed {
            background: var(--bs-light-success);
            color: var(--bs-success);
        }

        .report-empty-banner {
            padding: .75rem 1rem;
            border-radius: 10px;
            background: var(--bs-gray-100);
            color: var(--bs-gray-500);
            font-size: .85rem;
        }

        /* Calendar events styled like the status chips — rounded pill ends,
           full width so multi-day periods still read as one connected bar
           across the days they cover. !important is needed because
           FullCalendar sets its own default background/border via inline
           CSS custom properties, which otherwise beats a plain class rule. */
        .rd-event-pill {
            border-radius: 999px !important;
            border: none !important;
            padding: 3px 10px 3px 6px !important;
            font-weight: 600;
            font-size: .78rem;
        }

        .rd-event-pill.rd-status-pending,
        .rd-event-pill.rd-status-pending * {
            background: var(--bs-light-warning) !important;
            color: var(--bs-warning) !important;
        }

        .rd-event-pill.rd-status-reviewed,
        .rd-event-pill.rd-status-reviewed * {
            background: var(--bs-light-success) !important;
            color: var(--bs-success) !important;
        }

        /* Small avatar shown next to the reporter's name on each calendar row —
           just sizing/shape for the custom element, not a FullCalendar override. */
        .rd-event-avatar {
            width: 14px;
            height: 14px;
            object-fit: cover;
        }

        #rdReporterImage {
            width: 44px;
            height: 44px;
            min-width: 44px;
            border-radius: 50%;
            object-fit: cover;
        }

        #rdContent {
            max-height: 55vh;
            overflow-y: auto;
        }

        .modal-fullscreen #rdContent {
            max-height: calc(100vh - 230px);
        }

        /* Report content is stored as rich HTML (built from a template), so it
           renders as markup, not escaped text — this just keeps it from
           overflowing the modal's own width regardless of what it contains. */
        #rdContent img,
        #rdContent table {
            max-width: 100%;
        }
    </style>
@endpush
@section('content')
    @include('frontend.staff.pages.partials.breadcrumb')

    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <h5 class="mb-0">{{ $heading }}</h5>
            </div>

            {{-- Filter bar: search + account narrow the dataset server-side;
                 date navigation now lives in the calendar toolbar itself. --}}
            <form action="" method="GET" id="reportFilterForm">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                    <div class="search-field">
                        <i class="ti ti-search"></i>
                        <input type="search" name="search" value="{{ request('search') }}"
                            class="form-control rounded-pill" placeholder="Search by content..."
                            onkeydown="if(event.key==='Enter'){this.form.submit();}">
                    </div>

                    <select name="account" class="form-select form-select-sm rounded-pill"
                        style="max-width: 200px;" onchange="this.form.submit()">
                        <option value="all" @selected(!request()->filled('account') || request('account') === 'all')>
                            All {{ ucfirst($role) }}s
                        </option>
                        @foreach ($reporters as $reporter)
                            <option value="{{ $reporter->id }}" @selected((string) request('account') === (string) $reporter->id)>
                                {{ $reporter->name }}
                            </option>
                        @endforeach
                    </select>

                    @if (count($activeFilters))
                        <a href="{{ request()->fullUrlWithQuery(['search' => null, 'account' => null]) }}"
                            class="btn btn-light-danger btn-sm rounded-pill">
                            Clear filters
                        </a>
                    @endif
                </div>
            </form>

            {{-- Status legend / live filter — toggles which colors show on the calendar --}}
            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                <button type="button" class="status-legend legend-pending" data-status="pending">
                    <span class="dot" style="background: #FFAE1F;"></span>
                    Pending <span class="opacity-75">{{ $counts['pending'] }}</span>
                </button>
                <button type="button" class="status-legend legend-reviewed" data-status="reviewed">
                    <span class="dot" style="background: #13DEB9;"></span>
                    Reviewed <span class="opacity-75">{{ $counts['reviewed'] }}</span>
                </button>

                @if (count($activeFilters))
                    <span class="mx-1 text-muted">•</span>
                    @foreach ($activeFilters as $key => $chipLabel)
                        <span class="filter-chip">
                            {{ $chipLabel }}
                            <a href="{{ request()->fullUrlWithQuery([$key => null]) }}" aria-label="Remove filter">
                                <i class="ti ti-x fs-1"></i>
                            </a>
                        </span>
                    @endforeach
                @endif
            </div>

            @if ($events->isEmpty())
                <div class="report-empty-banner mb-3">
                    <i class="ti ti-info-circle me-1"></i>
                    No {{ strtolower($role) }} reports match your current filters. The calendar below will be empty
                    until that changes.
                </div>
            @endif

            <div id="reviewCalendar"></div>
        </div>
    </div>

    {{-- Single shared modal, populated from whichever event is clicked --}}
    <div class="modal fade" id="reportDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" id="rdModalDialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex align-items-center gap-2 overflow-hidden">
                        <img id="rdReporterImage" src="" alt="">
                        <div class="overflow-hidden">
                            <h6 class="mb-0 text-truncate" id="rdReporterName">—</h6>
                            <div class="fs-2 text-muted text-truncate" id="rdReporterRole">—</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-1 flex-shrink-0">
                        <button type="button" class="btn btn-icon btn-sm btn-light-secondary" id="rdFullscreenToggle"
                            title="Toggle fullscreen">
                            <i class="ti ti-arrows-maximize" id="rdFullscreenIcon"></i>
                        </button>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <span class="fw-semibold" id="rdPeriod">—</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="text-muted fs-2" id="rdSubmittedAt">—</span>
                        <span class="badge rounded-pill" id="rdStatusBadge">—</span>
                    </div>
                    <div class="mb-0" id="rdContent"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- If your layout already loads FullCalendar globally (it does on the
         existing /calendar page), remove this <script> tag to avoid loading
         it twice — this tag is only here so the view works standalone. --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.11/index.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const allEvents = @json($events);
            const statusStyles = {
                pending: 'bg-light-warning text-warning',
                reviewed: 'bg-light-success text-success',
            };
            const activeStatuses = new Set(['pending', 'reviewed']);

            const calendarEl = document.getElementById('reviewCalendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listMonth',
                },
                height: 720,
                dayMaxEvents: false,
                events: allEvents.filter(e => activeStatuses.has(e.extendedProps.status)),
                eventClassNames: function (arg) {
                    const status = arg.event.extendedProps.status;
                    return ['rd-event-pill', 'rd-status-' + status];
                },
                eventContent: function (arg) {
                    const props = arg.event.extendedProps;

                    const wrapper = document.createElement('div');
                    wrapper.className = 'd-flex align-items-center gap-1 overflow-hidden rd-event-row';

                    const avatar = document.createElement('img');
                    avatar.src = props.reporterImage;
                    avatar.alt = '';
                    avatar.className = 'rd-event-avatar rounded-circle flex-shrink-0';

                    const name = document.createElement('span');
                    name.className = 'text-truncate';
                    name.textContent = arg.event.title;

                    wrapper.appendChild(avatar);
                    wrapper.appendChild(name);

                    return { domNodes: [wrapper] };
                },
                eventClick: function (info) {
                    const dialog = document.getElementById('rdModalDialog');
                    dialog.classList.remove('modal-fullscreen');
                    document.getElementById('rdFullscreenIcon').className = 'ti ti-arrows-maximize';

                    const props = info.event.extendedProps;
                    document.getElementById('rdReporterImage').src = props.reporterImage;
                    document.getElementById('rdReporterName').textContent = props.reporterName;
                    document.getElementById('rdReporterRole').textContent = props.reporterRole;
                    document.getElementById('rdPeriod').textContent = props.periodLabel;
                    document.getElementById('rdSubmittedAt').textContent = 'Submitted ' + props.submittedAt;

                    const badge = document.getElementById('rdStatusBadge');
                    badge.className = 'badge rounded-pill ' + (statusStyles[props.status] || 'bg-light text-dark');
                    badge.textContent = props.status.charAt(0).toUpperCase() + props.status.slice(1);

                    // report_content is authored as rich HTML in this app (see the
                    // template markup in the payload), so it's rendered as markup
                    // here rather than escaped — same trust level as the rest of
                    // this staff-only review screen.
                    document.getElementById('rdContent').innerHTML = props.content;

                    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('reportDetailModal'));
                    modal.show();
                },
            });
            calendar.render();

            document.getElementById('rdFullscreenToggle').addEventListener('click', function () {
                const dialog = document.getElementById('rdModalDialog');
                const icon = document.getElementById('rdFullscreenIcon');
                const isFullscreen = dialog.classList.toggle('modal-fullscreen');
                icon.className = isFullscreen ? 'ti ti-arrows-minimize' : 'ti ti-arrows-maximize';
            });

            // Legend chips act as a live, client-side filter — no reload needed
            // since every matching report is already on the page.
            document.querySelectorAll('.status-legend').forEach(function (chip) {
                chip.addEventListener('click', function () {
                    const status = chip.dataset.status;
                    if (activeStatuses.has(status)) {
                        activeStatuses.delete(status);
                        chip.classList.add('is-off');
                    } else {
                        activeStatuses.add(status);
                        chip.classList.remove('is-off');
                    }
                    calendar.removeAllEvents();
                    calendar.addEventSource(allEvents.filter(e => activeStatuses.has(e.extendedProps.status)));
                });
            });
        });
    </script>
@endpush
