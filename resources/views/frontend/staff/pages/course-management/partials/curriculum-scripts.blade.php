<script>
    // ─── Curriculum Modal ───────────────────────────────────────────────────
    (function() {
        const $modal = $('#curriculumModal');
        const $body = $('#curriculumBody');

        function toast(type, message) {
            if (!message) return;
            iziToast[type]({
                title: '',
                message: message,
                position: 'bottomRight'
            });
        }

        function showErrors(xhr) {
            const res = xhr.responseJSON;
            if (res && res.errors) {
                Object.values(res.errors).flat().forEach(msg => toast('error', msg));
            } else {
                toast('error', (res && res.message) || 'Something went wrong.');
            }
        }

        function loadCurriculum(courseId, courseTitle) {
            $body.attr('data-course-id', courseId);
            $('#curriculumCourseTitle').text(courseTitle ? `– ${courseTitle}` : '');
            $body.html('<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>');
            $.get(`/staff/courses/${courseId}/curriculum`)
                .done(res => renderBody(res.html))
                .fail(xhr => {
                    showErrors(xhr);
                    $modal.modal('hide');
                });
        }

        function renderBody(html) {
            $body.html(html);
            initSortableChapters();
            initSortableLessons();
        }

        function initSortableChapters() {
            $('#chapterList').sortable({
                handle: '.chapter-drag-handle',
                axis: 'y',
                update: function() {
                    const courseId = $('#curriculumBodyInner').data('course-id');
                    const order = $('#chapterList .chapter-item').map(function() {
                        return $(this).data('chapter-id');
                    }).get();
                    $.ajax({
                        url: `/staff/courses/${courseId}/chapters/reorder`,
                        method: 'POST',
                        data: {
                            order: order,
                            _token: $('meta[name="csrf_token"]').attr('content')
                        },
                    }).fail(showErrors);
                }
            });
        }

        function initSortableLessons() {
            $('.lesson-list').each(function() {
                $(this).sortable({
                    handle: '.lesson-drag-handle',
                    axis: 'y',
                    update: function() {
                        const chapterId = $(this).data('chapter-id');
                        const order = $(this).find('.lesson-item').map(function() {
                            return $(this).data('lesson-id');
                        }).get();
                        $.ajax({
                            url: `/staff/chapters/${chapterId}/lessons/reorder`,
                            method: 'POST',
                            data: {
                                order: order,
                                _token: $('meta[name="csrf_token"]').attr('content')
                            },
                        }).fail(showErrors);
                    }
                });
            });
        }
        // Open modal
        $(document).on('click', '.btn-open-curriculum', function() {
            loadCurriculum($(this).data('id'), $(this).data('title'));
            $modal.modal('show');
        });
        // ── Add chapter ─────────────────────────────────────────────
        $(document).on('submit', '.add-chapter-form', function(e) {
            e.preventDefault();
            const $form = $(this);
            const courseId = $form.data('course-id');
            $.post(`/staff/courses/${courseId}/chapters`, {
                    title: $form.find('input[name=title]').val(),
                    _token: $('meta[name="csrf_token"]').attr('content'),
                })
                .done(res => {
                    renderBody(res.html);
                    toast('success', res.message);
                })
                .fail(showErrors);
        });
        // ── Edit chapter (toggle inline input) ──────────────────────
        $(document).on('click', '.btn-edit-chapter', function() {
            const $item = $(this).closest('.chapter-item');
            $item.find('.chapter-title-view, .chapter-view-actions').addClass('d-none');
            $item.find('.chapter-title-input, .chapter-edit-actions').removeClass('d-none');
            $item.find('.chapter-title-input').focus();
        });
        $(document).on('click', '.btn-cancel-chapter', function() {
            const $item = $(this).closest('.chapter-item');
            $item.find('.chapter-title-view, .chapter-view-actions').removeClass('d-none');
            $item.find('.chapter-title-input, .chapter-edit-actions').addClass('d-none');
        });
        $(document).on('click', '.btn-save-chapter', function() {
            const $item = $(this).closest('.chapter-item');
            const chapterId = $item.data('chapter-id');
            const title = $item.find('.chapter-title-input').val();
            $.ajax({
                    url: `/staff/chapters/${chapterId}`,
                    method: 'PUT',
                    data: {
                        title: title,
                        _token: $('meta[name="csrf_token"]').attr('content')
                    },
                })
                .done(res => {
                    renderBody(res.html);
                    toast('success', res.message);
                })
                .fail(showErrors);
        });
        // ── Delete chapter ───────────────────────────────────────────
        $(document).on('click', '.btn-delete-chapter', function() {
            if (!confirm('Delete this chapter and all its lessons?')) return;
            const chapterId = $(this).closest('.chapter-item').data('chapter-id');
            $.ajax({
                    url: `/staff/chapters/${chapterId}`,
                    method: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf_token"]').attr('content')
                    },
                })
                .done(res => {
                    renderBody(res.html);
                    toast('success', res.message);
                })
                .fail(showErrors);
        });
        // ── Add lesson ────────────────────────────────────────────────
        $(document).on('submit', '.add-lesson-form', function(e) {
            e.preventDefault();
            const $form = $(this);
            const chapterId = $form.data('chapter-id');
            $.post(`/staff/chapters/${chapterId}/lessons`, {
                    title: $form.find('input[name=title]').val(),
                    _token: $('meta[name="csrf_token"]').attr('content'),
                })
                .done(res => {
                    renderBody(res.html);
                    toast('success', res.message);
                })
                .fail(showErrors);
        });
        // ── Edit lesson ───────────────────────────────────────────────
        $(document).on('click', '.btn-edit-lesson', function() {
            const $item = $(this).closest('.lesson-item');
            $item.find('.lesson-title-view, .lesson-view-actions').addClass('d-none');
            $item.find('.lesson-title-input, .lesson-edit-actions').removeClass('d-none');
            $item.find('.lesson-title-input').focus();
        });
        $(document).on('click', '.btn-cancel-lesson', function() {
            const $item = $(this).closest('.lesson-item');
            $item.find('.lesson-title-view, .lesson-view-actions').removeClass('d-none');
            $item.find('.lesson-title-input, .lesson-edit-actions').addClass('d-none');
        });
        $(document).on('click', '.btn-save-lesson', function() {
            const $item = $(this).closest('.lesson-item');
            const lessonId = $item.data('lesson-id');
            const title = $item.find('.lesson-title-input').val();
            $.ajax({
                    url: `/staff/lessons/${lessonId}`,
                    method: 'PUT',
                    data: {
                        title: title,
                        _token: $('meta[name="csrf_token"]').attr('content')
                    },
                })
                .done(res => {
                    renderBody(res.html);
                    toast('success', res.message);
                })
                .fail(showErrors);
        });
        // ── Delete lesson ─────────────────────────────────────────────
        $(document).on('click', '.btn-delete-lesson', function() {
            if (!confirm('Delete this lesson?')) return;
            const lessonId = $(this).closest('.lesson-item').data('lesson-id');
            $.ajax({
                    url: `/staff/lessons/${lessonId}`,
                    method: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf_token"]').attr('content')
                    },
                })
                .done(res => {
                    renderBody(res.html);
                    toast('success', res.message);
                })
                .fail(showErrors);
        });
    })();
</script>
