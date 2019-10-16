<div>
    @foreach($field->options as $value => $label)
        <label class="block stack-xs custom-indicators" for="{{ $key.'-'.$value }}">
            <input {{ old($key, $field->selected) == $value ? 'checked="checked"':'' }}
            name="{{ $key }}[]"
                   value="{{ $value }}"
                   id="{{ $key.'-'.$value }}"
                   type="checkbox">
            <span class="custom-checkbox"></span>
            <strong>{{ $label }}</strong>
        </label>
    @endforeach
</div>

<error class="caption text-warning" field="{{ $key }}" :errors="errors.all()"></error>
