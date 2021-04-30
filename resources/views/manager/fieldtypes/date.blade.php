<div class="flex">
    @if($field->getPrepend())
        <div class="px-4 py-3 font-medium border border-r-0 bg-primary-50 border-primary-100 rounded-l-md text-primary-500">
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
        <div class="px-4 py-3 font-medium border border-l-0 bg-primary-50 border-primary-100 rounded-r-md text-primary-500">{!! $field->getAppend($locale ?? null) !!}</div>
    @endif
</div>
