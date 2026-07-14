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
                    editor.save(); // sync content back to the textarea
                });
            }
        });

        // Init Flatpickr
        flatpickr('.flatpickr', {
            enableTime: true,
            dateFormat: 'Y-m-d H:i',
            time_24hr: true,
        });

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
    });
</script>
