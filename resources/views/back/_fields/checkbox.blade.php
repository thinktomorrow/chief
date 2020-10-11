<div>
    @foreach($field->getOptions() as $value => $label)
        <label class="block stack-xs custom-indicators" for="{{ $key.'-'.$value }}">
            <input {{ in_array($value, (array)old($key, $field->getSelected())) ? 'checked="checked"':'' }}
                   name="{{ !$field->allowMultiple() ? $key : $key.'[]' }}"
                   value="{{ $value }}"
                   id="{{ $key.'-'.$value }}"
                   type="checkbox">
            <span class="custom-checkbox"></span>
            <strong>{{ $label }}</strong>
        </label>
    @endforeach
</div>

<error class="caption text-warning" field="{{ $key }}" :errors="errors.all()"></error>
