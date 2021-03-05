<div>
    <textarea
        name="{{ $field->getName($locale ?? null) }}"
        id="{{ $key }}"
        cols="5"
        rows="5"
        style="resize: vertical;"
        v-pre
    >{{ old($key, $field->getValue($locale ?? null)) }}</textarea>

    @if($field->hasCharacterCount())
        @include('chief::managers.fieldtypes.charactercount')
    @endif
</div>
