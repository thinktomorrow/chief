<div class="space-y-1">
    @foreach($field->getOptions() as $value => $label)
        <label for="{{ $key . '-' . $value }}" class="with-checkbox">
            <input
                type="checkbox"
                name="{{ !$field->allowMultiple() ? $key : $key.'[]' }}"
                id="{{ $key.'-'.$value }}"
                value="{{ $value }}"
                {{ in_array($value, (array)old($key, $field->getSelected() ?? $field->getValue($locale ?? null))) ? 'checked="checked"' : '' }}
            >

            <span>{{ $label }}</span>
        </label>
    @endforeach
</div>
