@csrf
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $blog->title ?? '') }}"
                        class="form-control @error('title') is-invalid @enderror" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Excerpt</label>
                    <textarea name="excerpt" rows="2" class="form-control @error('excerpt') is-invalid @enderror">{{ old('excerpt', $blog->excerpt ?? '') }}</textarea>
                    @error('excerpt')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3" id="content-field">
                    <label class="form-label">Content</label>
                    <textarea name="content" id="content-editor" rows="10"
                        class="form-control tinymce @error('content') is-invalid @enderror">{{ old('content', $blog->content ?? '') }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3 d-none" id="embed-field">
                    <label class="form-label">Embed Code / URL <span class="text-danger">*</span></label>
                    <textarea name="embed_url" id="embed-url-input" rows="4"
                        class="form-control @error('embed_url') is-invalid @enderror"
                        placeholder="Paste the Facebook/TikTok/YouTube embed code or a direct URL">{{ old('embed_url', $blog->embed_url ?? '') }}</textarea>
                    <div class="form-text">Paste the full embed code or a plain video URL.</div>
                    @error('embed_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <button type="button" id="fetch-thumbnail-btn" class="btn btn-sm btn-outline-primary mt-2">
                        <i class="bi bi-image"></i> Fetch Thumbnail from URL
                    </button>
                    <div id="fetch-thumbnail-status" class="form-text"></div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">SEO</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $blog->meta_title ?? '') }}"
                        class="form-control @error('meta_title') is-invalid @enderror">
                    @error('meta_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-0">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description" rows="2" class="form-control @error('meta_description') is-invalid @enderror">{{ old('meta_description', $blog->meta_description ?? '') }}</textarea>
                    @error('meta_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Publish</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                        @foreach (['draft' => 'Draft', 'scheduled' => 'Scheduled', 'published' => 'Published'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('status', $blog->status ?? 'draft') === $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3" id="published_at_wrapper">
                    <label class="form-label" id="published_at_label">Published At</label>
                    <input type="text" name="published_at"
                        value="{{ old('published_at', isset($blog->published_at) ? $blog->published_at->format('Y-m-d H:i') : '') }}"
                        class="form-control flatpickr @error('published_at') is-invalid @enderror" data-flatpickr
                        data-enable-time="true" autocomplete="off">
                    <div class="form-text" id="published_at_help">Leave blank to publish immediately when status is set
                        to Published.</div>
                    @error('published_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-check form-switch mb-0">
                    <input type="checkbox" name="is_featured" value="1" id="is_featured" class="form-check-input"
                        @checked(old('is_featured', $blog->is_featured ?? false))>
                    <label class="form-check-label" for="is_featured">Featured</label>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Type</h5>
            </div>
            <div class="card-body">
                <select name="type" id="type-select" class="form-select @error('type') is-invalid @enderror">
                    @foreach (['article', 'facebook', 'tiktok', 'youtube'] as $type)
                        <option value="{{ $type }}" @selected(old('type', $blog->type ?? 'article') === $type)>
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>
                @error('type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Thumbnail</h5>
            </div>
            <div class="card-body">
                <div id="thumbnail-preview">
                    @isset($blog)
                        @if ($blog->thumbnail_url)
                            <img src="{{ $blog->thumbnail_url }}" alt="" class="img-fluid rounded mb-3">
                        @endif
                    @endisset
                </div>
                <input type="file" name="thumbnail" accept="image/*"
                    class="form-control @error('thumbnail') is-invalid @enderror">
                <input type="hidden" name="fetched_thumbnail_url" id="fetched_thumbnail_url">
                @error('thumbnail')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>
<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">
        {{ isset($blog) ? 'Update Blog' : 'Create Blog' }}
    </button>
</div>
