<div class="space-y-1">
    @foreach($field->getOptions() as $value => $label)
        <label for="{{ $key . '-' . $value }}" class="with-radio">
            <input
                type="radio"
                name="{{ $key }}"
                value="{{ $value }}"
                id="{{ $key . '-' . $value }}"
                {{ old($key, $field->getSelected()) == $value ? 'checked="checked"' : null }}
            >

            <span>{!! $label !!}</span>
        </label>
    @endforeach
</div>
