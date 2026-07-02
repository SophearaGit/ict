<div class="modal-content">
    <form id="add_report_form" action="{{ route('staff.reports.store') }}" method="POST">
        @csrf
        <div class="modal-header d-flex align-items-center">
            <h4 class="modal-title">Add New Report</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Report Content</label>
                <textarea name="report_content" id="report_content" class="form-control" rows="7"></textarea>
                <div class="invalid-feedback d-block report_content_error"></div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-light-danger text-danger" data-bs-dismiss="modal">
                Close
            </button>
            <button type="submit" class="btn btn-primary submit_report_btn">
                <span class="btn-text">Submit Report</span>
                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
            </button>
        </div>
    </form>
</div>
<script>
    const reportTemplate = `
        <div style="max-width: 820px; margin: auto; font-family: Arial, Helvetica, sans-serif; color: #222; line-height: 1.2; font-size: 14px;">
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 22px;">
                <tbody>
                    <tr>
                        <td style="padding: 6px; border: 1px solid #ddd; width: 25%; background: #f7f7f7;"><strong>📅 Date</strong></td>
                        <td style="padding: 6px; border: 1px solid #ddd;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px; border: 1px solid #ddd; background: #f7f7f7;"><strong>👤 Name</strong></td>
                        <td style="padding: 6px; border: 1px solid #ddd;">&nbsp;</td>
                    </tr>
                </tbody>
            </table>
            <div style="font-weight: 600; margin-bottom: 6px;">🎯 Goals for this Week</div>
            <div style="border: 1px solid #ddd; border-radius: 3px; height: 90px; padding: 6px; background: #fafafa; margin-bottom: 14px;">&nbsp;</div>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 14px;">
                <thead>
                    <tr style="background: #f5f5f5;">
                        <th style="border: 1px solid #ddd; padding: 6px; width: 16%; text-align: left;">📅 Date</th>
                        <th style="border: 1px solid #ddd; padding: 6px; width: 28%; text-align: left;">📝 Task</th>
                        <th style="border: 1px solid #ddd; padding: 6px; width: 28%; text-align: left;">✅ Progress</th>
                        <th style="border: 1px solid #ddd; padding: 6px; width: 28%; text-align: left; color: #b02a37;">⚠️ Issue</th>
                    </tr>
                </thead>
                <tbody>
                    ${'<tr><td style="border: 1px solid #ddd; height: 28px;">&nbsp;</td><td style="border: 1px solid #ddd;">&nbsp;</td><td style="border: 1px solid #ddd;">&nbsp;</td><td style="border: 1px solid #ddd;">&nbsp;</td></tr>'.repeat(6)}
                </tbody>
            </table>
            <div style="font-weight: 600; margin-bottom: 6px;">💬 Issues &amp; Comments</div>
            <div style="border: 1px solid #ddd; border-radius: 3px; height: 90px; padding: 6px; background: #fafafa;">&nbsp;</div>
        </div>
    `;
    tinymce.init({
        selector: '#report_content',
        height: 580,
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        setup: function(editor) {
            editor.on('init', function() {
                editor.setContent(reportTemplate);
            });
        }
    });
    $('#add_report_form').on('submit', function(e) {
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
                    message: data.message || 'Report submitted successfully.',
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
