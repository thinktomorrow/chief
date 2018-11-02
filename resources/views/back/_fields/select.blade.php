<chief-multiselect
        name="{{ $key }}"
        :options='@json($field->options)'
        selected='@json(old($key, $field->selected ?? $manager->getFieldValue($key)))'
        :multiple='@json(!!$field->multiple)'
>
</chief-multiselect>

<error class="caption text-warning" field="{{ $key }}" :errors="errors.all()"></error>
