<div class="tab-pane fade" id="pills-students" role="tabpanel" aria-labelledby="pills-students-tab" tabindex="0">
    {{-- ── TOP BAR ── --}}
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h4 class="mb-0">
            Students
            <span class="badge text-bg-secondary fs-2 rounded-4 py-1 px-2 ms-1">
                {{ $course->students->count() }}
            </span>
        </h4>
        <div class="d-flex align-items-center gap-2">
            {{-- Select All --}}
            <div class="form-check mb-0">
                <input class="form-check-input" type="checkbox" id="selectAllStudents">
                <label class="form-check-label fs-3 text-muted" for="selectAllStudents">
                    All
                </label>
            </div>
        </div>
    </div>
    {{-- ── BULK ACTION TOOLBAR (appears when ≥1 checked) ── --}}
    <div id="bulkActionBar" class="d-none mb-3">
        <div class="d-flex align-items-center flex-wrap gap-2 p-3 rounded-3 border border-primary bg-light-primary">
            <span class="fw-semibold fs-3 text-primary me-1">
                <i class="ti ti-users me-1"></i>
                <span id="selectedCount">0</span> selected
            </span>
            <div class="vr mx-1"></div>
            {{-- Print --}}
            <button type="button" id="printCertificatesBtn" class="btn btn-sm btn-outline-primary">
                <i class="ti ti-certificate me-1"></i> Print Certificate
            </button>
            {{-- Move --}}
            <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal"
                data-bs-target="#moveCourseModal">
                <i class="ti ti-arrow-right me-1"></i> Move to Course
            </button>
            {{-- Remove --}}
            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                data-bs-target="#removeStudentModal">
                <i class="ti ti-user-minus me-1"></i> Remove
            </button>
            {{-- Clear selection --}}
            <button type="button" id="clearSelection" class="btn btn-sm btn-link text-muted ms-auto p-0">
                <i class="ti ti-x fs-4"></i>
            </button>
        </div>
    </div>
    {{-- ── STUDENT CARDS ── --}}
    <div class="row">
        @forelse ($course->students as $student)
            <div class="col-sm-6 col-lg-4">
                <div class="card hover-img position-relative student-card" data-student-id="{{ $student->id }}">
                    {{-- Checkbox --}}
                    <div class="form-check position-absolute top-0 end-0 m-2" style="z-index:2;">
                        <input class="form-check-input student-checkbox" type="checkbox" value="{{ $student->id }}"
                            data-name="{{ $student->name }}">
                    </div>
                    {{-- Selected overlay --}}
                    <div class="selected-overlay position-absolute top-0 start-0 w-100 h-100 d-none"
                        style="background:rgba(var(--bs-primary-rgb),0.06); border:2px solid var(--bs-primary); border-radius: inherit; pointer-events:none; z-index:1;">
                    </div>
                    <div class="card-body p-4 text-center border-bottom">
                        <img src="{{ asset($student->image == 'no-img.jpg' ? 'default-images/user/both.jpg' : $student->image) }}"
                            alt="{{ $student->name }}" class="rounded-circle mb-3" width="80" height="80"
                            style="object-fit:cover;">
                        <h5 class="fw-semibold mb-0 text-capitalize">
                            {{ Str::limit($student->name, 20) }}
                        </h5>
                        <span class="text-dark fs-2">
                            {{ $student->email }}
                        </span>
                    </div>
                    <ul class="px-2 py-2 bg-light list-unstyled d-flex align-items-center justify-content-center mb-0">
                        <li>
                            <a class="text-primary d-flex align-items-center justify-content-center p-2 fs-5 rounded-circle fw-semibold"
                                href="{{ $student->facebook ? $student->facebook : 'javascript:void(0)' }}"
                                target="_blank">
                                <i class="ti ti-brand-facebook"></i>
                            </a>
                        </li>
                        <li>
                            <a class="text-danger d-flex align-items-center justify-content-center p-2 fs-5 rounded-circle fw-semibold"
                                href="{{ $student->instagram ? $student->instagram : 'javascript:void(0)' }}"
                                target="_blank">
                                <i class="ti ti-brand-instagram"></i>
                            </a>
                        </li>
                        <li>
                            <a class="text-info d-flex align-items-center justify-content-center p-2 fs-5 rounded-circle fw-semibold"
                                href="{{ $student->github ? $student->github : 'javascript:void(0)' }}"
                                target="_blank">
                                <i class="ti ti-brand-github"></i>
                            </a>
                        </li>
                        <li>
                            <a class="text-secondary d-flex align-items-center justify-content-center p-2 fs-5 rounded-circle fw-semibold"
                                href="{{ $student->x ? $student->x : 'javascript:void(0)' }}" target="_blank">
                                <i class="ti ti-brand-twitter"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="ti ti-users fs-1 text-muted d-block mb-2"></i>
                        <h5 class="mb-0 text-muted">No students enrolled in this course.</h5>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
    {{-- ══════════════════════════════════════════
         MOVE TO COURSE MODAL
    ══════════════════════════════════════════ --}}
    <div class="modal fade" id="moveCourseModal" tabindex="-1" aria-labelledby="moveCourseModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="moveCourseModalLabel">
                        <i class="ti ti-arrow-right me-2 text-warning"></i>Move students to another course
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('staff.courses.move-student', $course->id) }}" id="moveForm">
                    @csrf
                    <div class="modal-body">
                        {{-- Selected student badges --}}
                        <p class="text-muted fs-3 mb-1">Moving:</p>
                        <div id="moveStudentNames" class="mb-3 d-flex flex-wrap gap-1"></div>
                        {{-- Hidden student_ids[] inputs injected by JS --}}
                        <div id="moveStudentInputs"></div>
                        <label class="form-label fw-semibold">Destination course</label>
                        <select name="target_course_id" id="targetCourseSelect" class="form-select" required>
                            <option value="">— Choose course —</option>
                            @foreach (\App\Models\ICTCourse::with('schedule')->where('id', '!=', $course->id)->orderBy('title')->get() as $c)
                                <option value="{{ $c->id }}">
                                    {{ $c->title }}
                                    @if ($c->schedule)
                                        — {{ ucfirst(str_replace('-', ' & ', $c->schedule->study_day)) }}
                                        ({{ \Carbon\Carbon::parse($c->schedule->start_time)->format('g:i') }}–{{ \Carbon\Carbon::parse($c->schedule->end_time)->format('g:i A') }})
                                        · {{ ucfirst($c->schedule->shift) }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning text-white">
                            <i class="ti ti-arrow-right me-1"></i> Move
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- ══════════════════════════════════════════
         REMOVE STUDENT MODAL
    ══════════════════════════════════════════ --}}
    <div class="modal fade" id="removeStudentModal" tabindex="-1" aria-labelledby="removeStudentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title text-danger" id="removeStudentModalLabel">
                        <i class="ti ti-alert-triangle me-2"></i>Remove students from course
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('staff.courses.remove-student', $course->id) }}"
                    id="removeForm">
                    @csrf
                    <div class="modal-body">
                        <p class="text-muted fs-3 mb-1">
                            The following students will be removed from
                            <strong class="text-dark">{{ $course->title }}</strong>:
                        </p>
                        <div id="removeStudentNames" class="mb-3 d-flex flex-wrap gap-1"></div>
                        {{-- Hidden student_ids[] inputs injected by JS --}}
                        <div id="removeStudentInputs"></div>
                        <div class="alert alert-danger d-flex align-items-start gap-2 py-2 fs-3 mb-0">
                            <i class="ti ti-info-circle fs-5 mt-1 flex-shrink-0"></i>
                            <span>This action cannot be undone. Attendance records will remain intact.</span>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="ti ti-trash me-1"></i> Yes, remove
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- ══════════════════════════════════════════
     BULK ACTION SCRIPTS
══════════════════════════════════════════ --}}
@push('scripts')
    <script>
        (function() {
            const bar = document.getElementById('bulkActionBar');
            const countLabel = document.getElementById('selectedCount');
            const selectAll = document.getElementById('selectAllStudents');
            const clearBtn = document.getElementById('clearSelection');
            const checkboxes = () => [...document.querySelectorAll('.student-checkbox')];
            const selected = () => checkboxes().filter(cb => cb.checked);
            // ── update toolbar visibility + count ──
            function syncBar() {
                const s = selected();
                countLabel.textContent = s.length;
                bar.classList.toggle('d-none', s.length === 0);
                // sync select-all state
                const total = checkboxes().length;
                selectAll.indeterminate = s.length > 0 && s.length < total;
                selectAll.checked = total > 0 && s.length === total;
            }
            // ── highlight card when checked ──
            function syncCardHighlight(cb) {
                const card = cb.closest('.student-card');
                const overlay = card?.querySelector('.selected-overlay');
                if (!overlay) return;
                overlay.classList.toggle('d-none', !cb.checked);
            }
            // ── wire individual checkboxes ──
            document.querySelectorAll('.student-checkbox').forEach(cb => {
                cb.addEventListener('change', function() {
                    syncCardHighlight(this);
                    syncBar();
                });
            });
            // ── select all ──
            selectAll?.addEventListener('change', function() {
                checkboxes().forEach(cb => {
                    cb.checked = this.checked;
                    syncCardHighlight(cb);
                });
                syncBar();
            });
            // ── clear selection ──
            clearBtn?.addEventListener('click', function() {
                checkboxes().forEach(cb => {
                    cb.checked = false;
                    syncCardHighlight(cb);
                });
                if (selectAll) selectAll.checked = false;
                syncBar();
            });
            // ── populate modal with selected student badges + hidden inputs ──
            function populateModal(namesEl, inputsEl) {
                const s = selected();
                namesEl.innerHTML = s.map(cb =>
                    `<span class="badge bg-light-primary text-primary fw-normal fs-2 py-1 px-2">
                <i class="ti ti-user me-1"></i>${cb.dataset.name ?? cb.value}
            </span>`
                ).join('');
                inputsEl.innerHTML = s.map(cb =>
                    `<input type="hidden" name="student_ids[]" value="${cb.value}">`
                ).join('');
            }
            document.getElementById('moveCourseModal')?.addEventListener('show.bs.modal', function() {
                populateModal(
                    document.getElementById('moveStudentNames'),
                    document.getElementById('moveStudentInputs')
                );
            });
            document.getElementById('removeStudentModal')?.addEventListener('show.bs.modal', function() {
                populateModal(
                    document.getElementById('removeStudentNames'),
                    document.getElementById('removeStudentInputs')
                );
            });
            // ── existing print certificate logic ──
            document.getElementById('printCertificatesBtn')?.addEventListener('click', function() {
                const s = selected();
                if (s.length === 0) {
                    alert('Please select at least one student.');
                    return;
                }
                fetch("{{ route('staff.certificates.print') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            students: s.map(cb => cb.value),
                            course_id: '{{ $course->id }}'
                        })
                    })
                    .then(res => res.blob())
                    .then(blob => {
                        const url = window.URL.createObjectURL(blob);
                        window.open(url, '_blank');
                    })
                    .catch(err => console.error(err));
            });
        })();
    </script>
@endpush
