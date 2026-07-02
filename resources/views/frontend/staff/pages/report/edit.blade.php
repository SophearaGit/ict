<div class="modal-content">
    <form id="edit_report_form" action="{{ route('staff.reports.update', $report->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="modal-header d-flex align-items-center">
            <h4 class="modal-title">Edit Report</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Report Content</label>
                <textarea name="report_content" id="report_content" class="form-control" rows="7">{!! $report->report_content !!}</textarea>
                <div class="invalid-feedback d-block report_content_error"></div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-light-danger text-danger" data-bs-dismiss="modal">
                Close
            </button>
            <button type="submit" class="btn btn-primary submit_report_btn">
                <span class="btn-text">Update Report</span>
                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
            </button>
        </div>
    </form>
</div>

<script>
    tinymce.init({
        selector: '#report_content',
        height: 580,
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    });

    $('#edit_report_form').on('submit', function(e) {
        e.preventDefault();
        tinymce.triggerSave();

        const $form = $(this);
        const $btn = $form.find('.submit_report_btn');
        $('.report_content_error').text('');

        $.ajax({
            method: 'POST',
            url: $form.attr('action'),
            data: $form.serialize(),
            beforeSend: function() {
                $btn.prop('disabled', true);
                $btn.find('.btn-text').addClass('d-none');
                $btn.find('.spinner-border').removeClass('d-none');
            },
            success: function(data) {
                iziToast.success({
                    message: data.message || 'Report updated successfully.',
                    position: 'bottomRight'
                });
                $('#dynamic_report_modal').modal('hide');
                window.location.reload();
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    $('.report_content_error').text(xhr.responseJSON.errors.report_content?.[0] ||
                        '');
                } else {
                    iziToast.error({
                        message: 'Something went wrong, try again.',
                        position: 'bottomRight'
                    });
                }
            },
            complete: function() {
                $btn.prop('disabled', false);
                $btn.find('.btn-text').removeClass('d-none');
                $btn.find('.spinner-border').addClass('d-none');
            }
        });
    });
</script>
