<div id="curriculumBodyInner" data-course-id="{{ $course->id }}">
    <ul class="chapter-list list-unstyled mb-3" id="chapterList">
        @forelse ($course->chapters as $chapter)
            <li class="chapter-item card mb-2" data-chapter-id="{{ $chapter->id }}">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ti ti-grip-vertical chapter-drag-handle text-muted" style="cursor:move;"></i>
                        <button type="button" class="btn btn-sm btn-link text-dark p-0 toggle-lessons"
                            data-bs-toggle="collapse" data-bs-target="#lessons-{{ $chapter->id }}">
                            <i class="ti ti-chevron-down"></i>
                        </button>
                        <span class="chapter-title-view flex-grow-1 fw-semibold">{{ $chapter->title }}</span>
                        <input type="text" class="form-control form-control-sm chapter-title-input d-none"
                            value="{{ $chapter->title }}">
                        <div class="d-flex gap-1 chapter-view-actions">
                            <button type="button" class="btn btn-sm btn-light-primary btn-edit-chapter" title="Rename">
                                <i class="ti ti-edit fs-4"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-light-danger btn-delete-chapter"
                                title="Delete">
                                <i class="ti ti-trash fs-4"></i>
                            </button>
                        </div>
                        <div class="d-flex gap-1 chapter-edit-actions d-none">
                            <button type="button" class="btn btn-sm btn-success btn-save-chapter" title="Save">
                                <i class="ti ti-check fs-4"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary btn-cancel-chapter" title="Cancel">
                                <i class="ti ti-x fs-4"></i>
                            </button>
                        </div>
                    </div>
                    <div class="collapse show mt-3 ps-4" id="lessons-{{ $chapter->id }}">
                        <ul class="lesson-list list-unstyled mb-2" data-chapter-id="{{ $chapter->id }}">
                            @forelse ($chapter->lessons as $lesson)
                                <li class="lesson-item d-flex align-items-center gap-2 py-1"
                                    data-lesson-id="{{ $lesson->id }}">
                                    <i class="ti ti-grip-vertical lesson-drag-handle text-muted"
                                        style="cursor:move;"></i>
                                    <i class="ti ti-file-text text-muted fs-4"></i>
                                    <span class="lesson-title-view flex-grow-1">{{ $lesson->title }}</span>
                                    <input type="text" class="form-control form-control-sm lesson-title-input d-none"
                                        value="{{ $lesson->title }}">
                                    <div class="d-flex gap-1 lesson-view-actions">
                                        <button type="button" class="btn btn-sm btn-light-primary btn-edit-lesson"
                                            title="Rename">
                                            <i class="ti ti-edit fs-4"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light-danger btn-delete-lesson"
                                            title="Delete">
                                            <i class="ti ti-trash fs-4"></i>
                                        </button>
                                    </div>
                                    <div class="d-flex gap-1 lesson-edit-actions d-none">
                                        <button type="button" class="btn btn-sm btn-success btn-save-lesson"
                                            title="Save">
                                            <i class="ti ti-check fs-4"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-secondary btn-cancel-lesson"
                                            title="Cancel">
                                            <i class="ti ti-x fs-4"></i>
                                        </button>
                                    </div>
                                </li>
                            @empty
                                <li class="text-muted fs-3 no-lesson-placeholder">No lessons yet.</li>
                            @endforelse
                        </ul>
                        <form class="add-lesson-form d-flex gap-2 mt-2" data-chapter-id="{{ $chapter->id }}">
                            <input type="text" class="form-control form-control-sm" name="title"
                                placeholder="New lesson title" required>
                            <button type="submit" class="btn btn-sm btn-info text-nowrap">
                                <i class="ti ti-plus"></i> Add Lesson
                            </button>
                        </form>
                    </div>
                </div>
            </li>
        @empty
            <li class="text-muted text-center py-4" id="noChapterPlaceholder">No chapters yet. Add one below.</li>
        @endforelse
    </ul>
    <form class="add-chapter-form d-flex gap-2" data-course-id="{{ $course->id }}">
        <input type="text" class="form-control" name="title" placeholder="New chapter title" required>
        <button type="submit" class="btn btn-info text-nowrap">
            <i class="ti ti-plus"></i> Add Chapter
        </button>
    </form>
</div>
