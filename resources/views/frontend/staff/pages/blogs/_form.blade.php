@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Syne:wght@700;800&display=swap"
        rel="stylesheet">
    <style>
        /* ── SHARED TOPBAR (used by create.blade.php / edit.blade.php) ── */
        .blog-form-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 22px;
        }

        .blog-form-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13.5px;
            font-weight: 600;
            color: #6b6b7a;
            text-decoration: none;
            transition: color 0.15s;
        }

        .blog-form-back:hover {
            color: #4f46e5;
        }

        .blog-form-heading h1 {
            font-family: 'DM Sans', sans-serif;
            font-weight: 700;
            font-size: 20px;
            color: #0f0e17;
            margin: 2px 0 3px;
            line-height: 1.3;
            letter-spacing: -0.01em;
        }

        .blog-form-heading p {
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            color: #8a8996;
            margin: 0;
        }

        .blog-btn-save {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 11px 20px;
            border-radius: 11px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13.5px;
            font-weight: 600;
            text-decoration: none;
            background: #0f0e17;
            color: #fff;
            border: 1px solid #0f0e17;
            cursor: pointer;
            transition: all 0.15s;
            white-space: nowrap;
        }

        .blog-btn-save:hover {
            background: #2749c4;
            border-color: #2749c4;
            color: #fff;
        }

        .blog-btn-cancel {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 11px 20px;
            border-radius: 11px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13.5px;
            font-weight: 600;
            text-decoration: none;
            background: #fff;
            color: #4b4a56;
            border: 1.5px solid #e5e5ec;
            cursor: pointer;
            transition: all 0.15s;
            white-space: nowrap;
        }

        .blog-btn-cancel:hover {
            background: #f4f4f8;
            color: #22212e;
        }

        /* ── FORM GRID ── */
        .blog-form-grid {
            display: grid;
            grid-template-columns: 1fr 360px;
            gap: 20px;
            align-items: start;
            font-family: 'DM Sans', sans-serif;
        }

        @media (max-width: 991.98px) {
            .blog-form-grid {
                grid-template-columns: 1fr;
            }
        }

        .blog-form-card {
            background: #fff;
            border: 1px solid #ecebf2;
            border-radius: 16px;
            padding: 22px 24px;
            margin-bottom: 20px;
            box-shadow: 0 1px 2px rgba(15, 14, 23, 0.03);
        }

        .blog-form-card:last-child {
            margin-bottom: 0;
        }

        .blog-form-card-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
            font-family: 'DM Sans', sans-serif;
            font-weight: 700;
            font-size: 15px;
            color: #0f0e17;
        }

        .blog-form-card-header i {
            color: #4f46e5;
            font-size: 17px;
        }

        .blog-field {
            margin-bottom: 18px;
        }

        .blog-field.mb-0 {
            margin-bottom: 0;
        }

        .blog-field-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #33323f;
            margin-bottom: 7px;
        }

        .blog-field-label .req {
            color: #dc2626;
            margin-left: 2px;
        }

        .blog-field-hint {
            font-size: 12px;
            color: #8a8996;
            margin-top: 7px;
        }

        .blog-field-count {
            font-size: 11.5px;
            color: #a3a2ad;
            text-align: right;
            margin-top: 5px;
        }

        .blog-input,
        .blog-textarea,
        .blog-select {
            display: block;
            width: 100%;
            padding: 11px 14px;
            border-radius: 11px;
            border: 1.5px solid #e5e5ec;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            color: #0f0e17;
            background: #fff;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .blog-input::placeholder,
        .blog-textarea::placeholder {
            color: #b5b4bf;
        }

        .blog-input:focus,
        .blog-textarea:focus,
        .blog-select:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
        }

        .blog-textarea {
            resize: vertical;
        }

        .blog-select {
            appearance: none;
            -webkit-appearance: none;
            padding-right: 38px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%236b6b7a' stroke-width='2.3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            cursor: pointer;
        }

        .blog-input.is-invalid,
        .blog-textarea.is-invalid,
        .blog-select.is-invalid {
            border-color: #dc2626;
        }

        .blog-invalid-feedback {
            color: #dc2626;
            font-size: 12px;
            margin-top: 6px;
        }

        /* ── TinyMCE width fix (was overflowing on narrow viewports) ── */
        #content-field .tox-tinymce {
            border-radius: 12px !important;
            border: 1.5px solid #e5e5ec !important;
            width: 100% !important;
            max-width: 100% !important;
        }

        /* ── Fetch-thumbnail button ── */
        .blog-fetch-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 10px;
            padding: 9px 16px;
            border-radius: 10px;
            border: 1.5px solid #e5e5ec;
            background: #fff;
            color: #4f46e5;
            font-family: 'DM Sans', sans-serif;
            font-size: 12.5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s;
        }

        .blog-fetch-btn:hover {
            background: #eef0ff;
            border-color: #4f46e5;
        }

        .blog-fetch-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* ── Featured toggle ── */
        .blog-toggle-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 13px 14px;
            background: #fafafe;
            border: 1px solid #ecebf2;
            border-radius: 12px;
        }

        .blog-toggle-title {
            font-size: 13.5px;
            font-weight: 600;
            color: #0f0e17;
        }

        .blog-toggle-sub {
            font-size: 11.5px;
            color: #8a8996;
            margin-top: 2px;
        }

        .blog-toggle {
            position: relative;
            display: inline-block;
            width: 42px;
            height: 25px;
            flex: 0 0 auto;
        }

        .blog-toggle input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .blog-toggle span {
            position: absolute;
            inset: 0;
            background: #d9d8e3;
            border-radius: 999px;
            cursor: pointer;
            transition: background 0.15s;
        }

        .blog-toggle span::before {
            content: '';
            position: absolute;
            width: 19px;
            height: 19px;
            left: 3px;
            top: 3px;
            background: #fff;
            border-radius: 50%;
            transition: transform 0.15s;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.18);
        }

        .blog-toggle input:checked+span {
            background: #4f46e5;
        }

        .blog-toggle input:checked+span::before {
            transform: translateX(17px);
        }

        .blog-toggle input:focus-visible+span {
            outline: 2px solid #4f46e5;
            outline-offset: 2px;
        }

        /* ── Thumbnail dropzone ── */
        .blog-thumb-preview {
            margin-bottom: 14px;
        }

        .blog-thumb-preview:empty {
            display: none;
        }

        .blog-thumb-preview img {
            width: 100%;
            display: block;
            border-radius: 12px;
            border: 1px solid #ecebf2;
        }

        .blog-thumb-dropzone {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            text-align: center;
            padding: 26px 14px;
            border: 2px dashed #d7d6e0;
            border-radius: 14px;
            background: #fafafe;
            cursor: pointer;
            transition: all 0.15s;
        }

        .blog-thumb-dropzone:hover {
            border-color: #4f46e5;
            background: rgba(79, 70, 229, 0.04);
        }

        .blog-thumb-dropzone i {
            font-size: 24px;
            color: #4f46e5;
            margin-bottom: 4px;
        }

        .blog-thumb-dropzone-text {
            font-size: 13px;
            font-weight: 600;
            color: #33323f;
        }

        .blog-thumb-dropzone small {
            font-size: 11.5px;
            color: #8a8996;
        }

        .blog-thumb-input {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        /* ── Bottom action bar ── */
        .blog-form-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 4px;
        }

        @media (max-width: 575.98px) {
            .blog-form-card {
                padding: 18px 16px;
            }

            .blog-form-footer {
                flex-direction: column-reverse;
            }

            .blog-form-footer .blog-btn-save,
            .blog-form-footer .blog-btn-cancel {
                justify-content: center;
                width: 100%;
            }
        }
    </style>
@endpush

@csrf
<div class="blog-form-grid">
    <div class="blog-form-main">
        <div class="blog-form-card">
            <div class="blog-form-card-header"><i class="ti ti-file-text"></i> Content</div>
            <div class="blog-field">
                <label class="blog-field-label" for="title-input">Title<span class="req">*</span></label>
                <input type="text" id="title-input" name="title" maxlength="255"
                    value="{{ old('title', $blog->title ?? '') }}"
                    class="blog-input @error('title') is-invalid @enderror" required
                    placeholder="e.g. 5 Tips for Getting the Most Out of Your Laravel Course">
                @error('title')
                    <div class="blog-invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="blog-field">
                <label class="blog-field-label" for="excerpt-input">Excerpt</label>
                <textarea id="excerpt-input" name="excerpt" maxlength="500" rows="3"
                    class="blog-textarea @error('excerpt') is-invalid @enderror"
                    placeholder="A short teaser shown in listings and search results...">{{ old('excerpt', $blog->excerpt ?? '') }}</textarea>
                <div class="blog-field-count" data-counter-for="excerpt-input" data-max="500">0 / 500</div>
                @error('excerpt')
                    <div class="blog-invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="blog-field mb-0" id="content-field">
                <label class="blog-field-label">Content</label>
                <textarea name="content" id="content-editor" rows="10"
                    class="blog-textarea tinymce @error('content') is-invalid @enderror">{{ old('content', $blog->content ?? '') }}</textarea>
                @error('content')
                    <div class="blog-invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="blog-field mb-0 d-none" id="embed-field">
                <label class="blog-field-label">Embed Code / URL<span class="req">*</span></label>
                <textarea name="embed_url" id="embed-url-input" rows="4"
                    class="blog-textarea @error('embed_url') is-invalid @enderror"
                    placeholder="Paste the Facebook/TikTok/YouTube embed code or a direct URL">{{ old('embed_url', $blog->embed_url ?? '') }}</textarea>
                <div class="blog-field-hint">Paste the full embed code or a plain video URL.</div>
                @error('embed_url')
                    <div class="blog-invalid-feedback">{{ $message }}</div>
                @enderror

                <button type="button" id="fetch-thumbnail-btn" class="blog-fetch-btn">
                    <i class="ti ti-photo"></i> Fetch Thumbnail from URL
                </button>
                <div id="fetch-thumbnail-status" class="blog-field-hint"></div>
            </div>
        </div>

        <div class="blog-form-card">
            <div class="blog-form-card-header"><i class="ti ti-search"></i> SEO</div>
            <div class="blog-field">
                <label class="blog-field-label" for="meta-title-input">Meta Title</label>
                <input type="text" id="meta-title-input" name="meta_title" maxlength="255"
                    value="{{ old('meta_title', $blog->meta_title ?? '') }}"
                    class="blog-input @error('meta_title') is-invalid @enderror"
                    placeholder="Defaults to the post title if left blank">
                @error('meta_title')
                    <div class="blog-invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="blog-field mb-0">
                <label class="blog-field-label" for="meta-description-input">Meta Description</label>
                <textarea id="meta-description-input" name="meta_description" maxlength="500" rows="2"
                    class="blog-textarea @error('meta_description') is-invalid @enderror"
                    placeholder="Defaults to the excerpt if left blank">{{ old('meta_description', $blog->meta_description ?? '') }}</textarea>
                <div class="blog-field-count" data-counter-for="meta-description-input" data-max="500">0 / 500</div>
                @error('meta_description')
                    <div class="blog-invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="blog-form-side">
        <div class="blog-form-card">
            <div class="blog-form-card-header"><i class="ti ti-rocket"></i> Publish</div>
            <div class="blog-field">
                <label class="blog-field-label" for="status">Status</label>
                <select name="status" id="status" class="blog-select @error('status') is-invalid @enderror">
                    @foreach (['draft' => 'Draft', 'scheduled' => 'Scheduled', 'published' => 'Published'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('status', $blog->status ?? 'draft') === $value)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('status')
                    <div class="blog-invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="blog-field" id="published_at_wrapper">
                <label class="blog-field-label" id="published_at_label" for="published_at_input">Published At</label>
                <input type="text" id="published_at_input" name="published_at"
                    value="{{ old('published_at', isset($blog->published_at) ? $blog->published_at->format('Y-m-d H:i') : '') }}"
                    class="blog-input flatpickr @error('published_at') is-invalid @enderror" data-flatpickr
                    data-enable-time="true" autocomplete="off" placeholder="Pick a date & time">
                <div class="blog-field-hint" id="published_at_help">Leave blank to publish immediately when status is
                    set to Published.</div>
                @error('published_at')
                    <div class="blog-invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="blog-toggle-row mb-0">
                <div>
                    <div class="blog-toggle-title">Featured</div>
                    <div class="blog-toggle-sub">Pin to the top of the blog list</div>
                </div>
                <label class="blog-toggle">
                    <input type="checkbox" name="is_featured" value="1" id="is_featured"
                        @checked(old('is_featured', $blog->is_featured ?? false))>
                    <span></span>
                </label>
            </div>
        </div>

        <div class="blog-form-card">
            <div class="blog-form-card-header"><i class="ti ti-category"></i> Type</div>
            <div class="blog-field mb-0">
                <select name="type" id="type-select" class="blog-select @error('type') is-invalid @enderror">
                    @foreach (['article', 'facebook', 'tiktok', 'youtube'] as $type)
                        <option value="{{ $type }}" @selected(old('type', $blog->type ?? 'article') === $type)>
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>
                @error('type')
                    <div class="blog-invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="blog-form-card mb-0">
            <div class="blog-form-card-header"><i class="ti ti-photo"></i> Thumbnail</div>
            <div id="thumbnail-preview" class="blog-thumb-preview">
                @isset($blog)
                    @if ($blog->thumbnail_url)
                        <img src="{{ $blog->thumbnail_url }}" alt="">
                    @endif
                @endisset
            </div>
            <label class="blog-thumb-dropzone" for="thumbnail-input">
                <i class="ti ti-cloud-upload"></i>
                <span class="blog-thumb-dropzone-text" id="thumbnail-filename">Click to upload an image</span>
                <small>JPG, PNG or WEBP, up to 2MB</small>
            </label>
            <input type="file" id="thumbnail-input" name="thumbnail" accept="image/*"
                class="blog-thumb-input @error('thumbnail') is-invalid @enderror">
            <input type="hidden" name="fetched_thumbnail_url" id="fetched_thumbnail_url">
            @error('thumbnail')
                <div class="blog-invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="blog-form-footer">
    <a href="{{ route('staff.blogs.index') }}" class="blog-btn-cancel">Cancel</a>
    <button type="submit" class="blog-btn-save">
        <i class="ti ti-check"></i> {{ isset($blog) ? 'Update Blog' : 'Create Blog' }}
    </button>
</div>
