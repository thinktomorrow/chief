@if($field->getAppend() || $field->getPrepend())
    <div class="input-addon">
        @if($field->getPrepend())
            <div class="addon inset-s">{!! $field->getPrepend($locale ?? null) !!}</div>
        @endif

        <input
            type="text"
            name="{{ $field->getName($locale ?? null) }}"
            id="{{ $field->getDottedName($locale ?? null) }}"
            class="input"
            placeholder="{{ $field->getPlaceholder($locale ?? null) }}"
            value="{{ old($field->getDottedName($locale ?? null), $field->getValue($locale ?? null)) }}"
        >

        @if($field->getAppend())
            <div class="addon inset-s">{!! $field->getAppend($locale ?? null) !!}</div>
        @endif
    </div>
@else
    <input
        type="text"
        name="{{ $field->getName($locale ?? null) }}"
        id="{{ $field->getDottedName($locale ?? null) }}"
        class="input"
        placeholder="{{ $field->getPlaceholder($locale ?? null) }}"
        value="{{ old($field->getDottedName($locale ?? null), $field->getValue($locale ?? null)) }}"
    >
@endif

@if($field->hasCharacterCount())
    @include('chief::managers.fieldtypes.charactercount')
@endif

{{--<error class="caption text-warning" field="{{ $field->getDottedName($locale ?? null) }}" :errors="errors.all()"></error>--}}
