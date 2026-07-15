<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type-select');
        const contentField = document.getElementById('content-field');
        const embedField = document.getElementById('embed-field');
        // Init TinyMCE
        tinymce.init({
            selector: '#content-editor',
            height: 400,
            menubar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap',
                'preview', 'anchor', 'searchreplace', 'visualblocks', 'code',
                'fullscreen', 'insertdatetime', 'media', 'table', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | bold italic underline forecolor | ' +
                'alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist outdent indent | link image media | removeformat | code fullscreen',
            content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; font-size: 14px }',
            branding: false,
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save();
                });
            }
        });
        // Init Flatpickr
        flatpickr('.flatpickr', {
            enableTime: true,
            dateFormat: 'Y-m-d H:i',
            time_24hr: true,
        });
        // Type toggle (article vs embed types)
        function toggleFields() {
            if (typeSelect.value === 'article') {
                contentField.classList.remove('d-none');
                embedField.classList.add('d-none');
                const editor = tinymce.get('content-editor');
                if (editor) editor.show();
            } else {
                contentField.classList.add('d-none');
                embedField.classList.remove('d-none');
                const editor = tinymce.get('content-editor');
                if (editor) editor.hide();
            }
        }
        typeSelect.addEventListener('change', toggleFields);
        toggleFields();
        // Status / Published At field visibility + labels
        const statusSelect = document.getElementById('status');
        const publishedWrapper = document.getElementById('published_at_wrapper');
        const publishedHelp = document.getElementById('published_at_help');
        const publishedLabel = document.getElementById('published_at_label');
        const publishedInput = publishedWrapper.querySelector('input[name="published_at"]');

        function updatePublishedAtField() {
            if (statusSelect.value === 'scheduled') {
                publishedWrapper.classList.remove('d-none');
                publishedLabel.textContent = 'Scheduled For';
                publishedHelp.textContent = 'This blog will automatically go live at the date/time you pick.';
            } else {
                publishedWrapper.classList.add('d-none');
                publishedInput.value = ''; // clear so no stale value gets submitted
            }
        }
        statusSelect.addEventListener('change', updatePublishedAtField);
        updatePublishedAtField();
        // Fetch Thumbnail from embed URL (YouTube / TikTok / Facebook)
        const fetchBtn = document.getElementById('fetch-thumbnail-btn');
        const embedInput = document.getElementById('embed-url-input');
        const statusEl = document.getElementById('fetch-thumbnail-status');
        const previewEl = document.getElementById('thumbnail-preview');
        const fetchedUrlInput = document.getElementById('fetched_thumbnail_url');
        if (fetchBtn) {
            fetchBtn.addEventListener('click', async function() {
                const url = embedInput.value.trim();
                const type = typeSelect.value;
                if (!url) {
                    statusEl.textContent = 'Paste a URL first.';
                    statusEl.classList.remove('text-success');
                    statusEl.classList.add('text-danger');
                    return;
                }
                statusEl.textContent = 'Fetching...';
                statusEl.classList.remove('text-danger', 'text-success');
                fetchBtn.disabled = true;
                try {
                    const res = await fetch(
                        '{{ route('admin.blogs.fetch-thumbnail') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')
                                    .value,
                            },
                            body: JSON.stringify({
                                embed_url: url,
                                type: type
                            }),
                        });
                    const data = await res.json();
                    if (data.success) {
                        previewEl.innerHTML =
                            `<img src="${data.thumbnail_url}" class="img-fluid rounded mb-3">`;
                        fetchedUrlInput.value = data.thumbnail_url;
                        statusEl.textContent = 'Thumbnail found!';
                        statusEl.classList.remove('text-danger');
                        statusEl.classList.add('text-success');
                    } else {
                        statusEl.textContent = data.message || 'Could not fetch thumbnail.';
                        statusEl.classList.remove('text-success');
                        statusEl.classList.add('text-danger');
                    }
                } catch (e) {
                    statusEl.textContent = 'Something went wrong.';
                    statusEl.classList.remove('text-success');
                    statusEl.classList.add('text-danger');
                } finally {
                    fetchBtn.disabled = false;
                }
            });
        }
    });
</script>
