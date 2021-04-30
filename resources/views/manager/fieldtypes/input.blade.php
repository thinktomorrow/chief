<div class="flex">
    @if($field->getPrepend())
        <div class="px-4 py-3 font-medium border border-r-0 bg-primary-50 border-primary-100 rounded-l-md text-primary-500">
            {!! $field->getPrepend($locale ?? null) !!}
        </div>
    @endif

    <input
        type="text"
        name="{{ $field->getName($locale ?? null) }}"
        id="{{ $field->getDottedName($locale ?? null) }}"
        placeholder="{{ $field->getPlaceholder($locale ?? null) }}"
        value="{{ old($field->getDottedName($locale ?? null), $field->getValue($locale ?? null)) }}"
        class="{{ $field->getPrepend() ? 'with-prepend' : null }} {{ $field->getAppend() ? 'with-append' : null }}"
    >

    @if($field->getAppend())
        <div class="px-4 py-3 font-medium border border-l-0 bg-primary-50 border-primary-100 rounded-r-md text-primary-500">{!! $field->getAppend($locale ?? null) !!}</div>
    @endif
</div>

@if($field->hasCharacterCount())
    @include('chief::manager.fieldtypes.charactercount')
@endif
