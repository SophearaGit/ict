<div class="modal-content">
    <form action="{{ route('staff.reports.update', $report->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="modal-header d-flex align-items-center">
            <h4 class="modal-title">
                Edit Report
            </h4>

            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

            <!-- Report Content -->
            <div class="mb-3">
                <label class="form-label">Report Content</label>
                <textarea name="report_content" class="form-control" rows="7" placeholder="Update your report here...">{!! $report->report_content !!}</textarea>
            </div>

        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-light-danger text-danger" data-bs-dismiss="modal">
                Close
            </button>

            <button type="submit" class="btn btn-primary">
                Update Report
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
