{{--
    Reusable repeater for one-line bullet lists (learning points / requirements).

    Usage:
        @include('frontend.staff.pages.partials.course-repeater-section', [
            'name'        => 'learning_points',
            'label'       => "What you'll learn",
            'icon'        => 'ti-list-check',
            'placeholder' => "e.g. Build responsive and modern websites from scratch",
            'items'       => old('learning_points', $course->learningPoints->pluck('content')->toArray() ?? []),
        ])
--}}
<div class="section-label">
    <span class="icon-wrap">
        <i class="ti {{ $icon }}"></i>
    </span>
    {{ $label }}
</div>

<div class="form-section repeater-section" data-repeater="{{ $name }}">
    <div class="repeater-list" id="{{ $name }}-list">

        @foreach ($items ?: [''] as $item)
            <div class="repeater-row">
                <i class="ti ti-grip-vertical repeater-drag" aria-hidden="true"></i>

                <input type="text" class="form-control repeater-input" name="{{ $name }}[]"
                    value="{{ $item }}" placeholder="{{ $placeholder }}" maxlength="255">

                <button type="button" class="btn btn-sm btn-outline-danger repeater-remove" aria-label="Remove">
                    <i class="ti ti-trash"></i>
                </button>
            </div>
        @endforeach

    </div>

    <button type="button" class="btn btn-sm btn-outline-info repeater-add mt-2" data-target="{{ $name }}-list"
        data-name="{{ $name }}[]" data-placeholder="{{ $placeholder }}">
        <i class="ti ti-plus me-1"></i>
        Add item
    </button>

    <x-input-error :messages="$errors->get($name . '.*')" class="text-danger mt-2" />
</div>
