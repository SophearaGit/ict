@if ($paywayFields)
    @foreach ($paywayFields as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach
@endif
