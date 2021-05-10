<div class="flex">
    @if($field->getPrepend())
        <div class="prepend-to-input">
            {!! $field->getPrepend($locale ?? null) !!}
        </div>
    @endif

    <input
        type="text"
        name="{{ $field->getName($locale ?? null) }}"
        id="{{ $field->getId($locale ?? null) }}"
        placeholder="{{ $field->getPlaceholder($locale ?? null) }}"
        value="{{ old($field->getDottedName($locale ?? null), $field->getValue($locale ?? null)) }}"
        class="{{ $field->getPrepend() ? 'with-prepend' : null }} {{ $field->getAppend() ? 'with-append' : null }}"
    >

    @if($field->getAppend())
        <div class="append-to-input">
            {!! $field->getAppend($locale ?? null) !!}
        </div>
    @endif
</div>

@if($field->hasCharacterCount())
    @include('chief::manager.fieldtypes.charactercount')
@endif
