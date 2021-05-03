<div class="flex">
    @if($field->getPrepend())
        <div class="prepend-to-input">
            {!! $field->getPrepend($locale ?? null) !!}
        </div>
    @endif

    <input
        max="3000-01-01"
        type="date"
        name="{{ $field->getName($locale ?? null) }}"
        id="{{ $key }}"
        placeholder="{{ $field->getPlaceholder($locale ?? null) }}"
        value="{{ old($key, optional($field->getValue($locale ?? null))->format('Y-m-d')) }}"
        class="{{ $field->getPrepend() ? 'with-prepend' : null }} {{ $field->getAppend() ? 'with-append' : null }}"
    >

    @if($field->getAppend())
        <div class="append-to-input">
            {!! $field->getAppend($locale ?? null) !!}
        </div>
    @endif
</div>
