<div>
    <textarea
        name="{{ $field->getName($locale ?? null) }}"
        id="{{ $key }}"
        cols="5"
        rows="5"
        class="w-full"
        style="resize: vertical;"
        v-pre
    >{{ old($key, $field->getValue($locale ?? null)) }}</textarea>

    @if($field->hasCharacterCount())
        @include('chief::manager.fieldtypes.charactercount')
    @endif
</div>
