<div class="space-y-1">
    @foreach($field->getOptions() as $value => $label)
        <label for="{{ $field->getId($locale ?? null) }}{{ $value }}" class="with-radio">
            <input
                type="radio"
                name="{{ $key }}"
                value="{{ $value }}"
                id="{{ $field->getId($locale ?? null) }}{{ $value }}"
                {{ old($key, $field->getSelected()) == $value ? 'checked="checked"' : null }}
                {!! $field->isToggle($value) ? 'data-toggle-field-trigger="'.$field->getToggleAttributeValue($value).'"' : '' !!}
            >

            <span>{!! $label !!}</span>
        </label>
    @endforeach
</div>
