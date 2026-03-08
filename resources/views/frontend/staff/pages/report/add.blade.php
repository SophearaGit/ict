<div class="modal-content">
    <form action="{{ route('staff.reports.store') }}" method="POST">
        @csrf

        <div class="modal-header d-flex align-items-center">
            <h4 class="modal-title">
                Add New Report
            </h4>

            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

            <!-- Report Content -->
            <div class="mb-3">
                <label class="form-label">Report Content</label>
                <textarea name="report_content" class="form-control" rows="7" placeholder="Write your report here...">
                    <div style="max-width: 820px; margin: auto; font-family: Arial, Helvetica, sans-serif; color: #222; line-height: 1.2; font-size: 14px;">
                    <p>&nbsp;</p>
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 12px;">
                    <tbody>
                    <tr>
                    <td style="padding: 6px; border: 1px solid #ddd; width: 25%; background: #f7f7f7;"><strong>📅 Date</strong></td>
                    <td style="padding: 6px; border: 1px solid #ddd;">&nbsp;</td>
                    </tr>
                    {{-- <tr>
                    <td style="padding: 6px; border: 1px solid #ddd; background: #f7f7f7;"><strong>🏢 Department</strong></td>
                    <td style="padding: 6px; border: 1px solid #ddd;">&nbsp;</td>
                    </tr> --}}
                    <tr>
                    <td style="padding: 6px; border: 1px solid #ddd; background: #f7f7f7;"><strong>👤 Name</strong></td>
                    <td style="padding: 6px; border: 1px solid #ddd;">&nbsp;</td>
                    </tr>
                    </tbody>
                    </table>
                    <p><!-- Goals Section --></p>
                    <p>&nbsp;</p>
                    <div style="margin-bottom: 14px;"><br>
                    <div style="font-weight: 600; margin-bottom: 4px;">🎯 Goals for this week</div>
                    <br>
                    <div style="border: 1px solid #ddd; border-radius: 3px; height: 90px; padding: 6px; background: #fafafa;">&nbsp;</div>
                    </div>
                    <p><!-- Task Progress Table --></p>
                    <p>&nbsp;</p>
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 14px;">
                    <thead>
                    <tr style="background: #f5f5f5;">
                    <th style="border: 1px solid #ddd; padding: 6px; width: 18%; text-align: left;">📅 Date</th>
                    <th style="border: 1px solid #ddd; padding: 6px; width: 41%; text-align: left;">📝 Task</th>
                    <th style="border: 1px solid #ddd; padding: 6px; width: 41%; text-align: left;">📊 Progress</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                    <td style="border: 1px solid #ddd; height: 28px;">&nbsp;</td>
                    <td style="border: 1px solid #ddd;">&nbsp;</td>
                    <td style="border: 1px solid #ddd;">&nbsp;</td>
                    </tr>
                    <tr>
                    <td style="border: 1px solid #ddd; height: 28px;">&nbsp;</td>
                    <td style="border: 1px solid #ddd;">&nbsp;</td>
                    <td style="border: 1px solid #ddd;">&nbsp;</td>
                    </tr>
                    <tr>
                    <td style="border: 1px solid #ddd; height: 28px;">&nbsp;</td>
                    <td style="border: 1px solid #ddd;">&nbsp;</td>
                    <td style="border: 1px solid #ddd;">&nbsp;</td>
                    </tr>
                    <tr>
                    <td style="border: 1px solid #ddd; height: 28px;">&nbsp;</td>
                    <td style="border: 1px solid #ddd;">&nbsp;</td>
                    <td style="border: 1px solid #ddd;">&nbsp;</td>
                    </tr>
                    <tr>
                    <td style="border: 1px solid #ddd; height: 28px;">&nbsp;</td>
                    <td style="border: 1px solid #ddd;">&nbsp;</td>
                    <td style="border: 1px solid #ddd;">&nbsp;</td>
                    </tr>
                    <tr>
                    <td style="border: 1px solid #ddd; height: 28px;">&nbsp;</td>
                    <td style="border: 1px solid #ddd;">&nbsp;</td>
                    <td style="border: 1px solid #ddd;">&nbsp;</td>
                    </tr>
                    <tr>
                    <td style="border: 1px solid #ddd; height: 28px;">&nbsp;</td>
                    <td style="border: 1px solid #ddd;">&nbsp;</td>
                    <td style="border: 1px solid #ddd;">&nbsp;</td>
                    </tr>
                    </tbody>
                    </table>
                    <p><!-- Issues Section --></p>
                    <div>
                    <div style="font-weight: 600; margin-bottom: 4px;">&nbsp;</div>
                    <div style="font-weight: 600; margin-bottom: 4px;">⚠️ Issues &amp; Comments</div>
                    <br>
                    <div style="border: 1px solid #ddd; border-radius: 3px; height: 90px; padding: 6px; background: #fafafa;">&nbsp;</div>
                    </div>
                    </div>
                    <p>&nbsp;</p>
                </textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-light-danger text-danger" data-bs-dismiss="modal">
                Close
            </button>

            <button type="submit" class="btn btn-primary">
                Submit Report
            </button>
        </div>
    </form>
</div>

<script>
    tinymce.init({
        selector: 'textarea',
        height: 580,
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    });
</script>
