@props(['messages'])

@if ($messages)
    <ul
        {{ $attributes->merge(['class' => 'text-sm text-red-600 dark:text-red-400 space-y-1 ', 'style' => 'list-style-type: none; padding-left: 0rem;']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
