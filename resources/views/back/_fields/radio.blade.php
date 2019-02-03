<radio-options inline-template :errors="errors" default-type="{{ old($key, $field->selected) }}">
    <div>
        @foreach($field->options as $value => $label)
            <label class="block stack-xs custom-indicators" for="{{ $key.'-'.$value }}">
                <input v-on:click="changeType({{ $value }})" {{ old($key, $field->selected) == $value ? 'checked="checked"':'' }}
                name="{{ $key }}"
                       value="{{ $value }}"
                       id="{{ $key.'-'.$value }}"
                       type="radio">
                <span class="custom-radiobutton --primary"></span>
                <strong>{{ $label }}</strong>
            </label>
        @endforeach
    </div>
</radio-options>

<error class="caption text-warning" field="{{ $key }}" :errors="errors.all()"></error>
