<radio-options inline-template :errors="errors" default-type="{{ old($key, $field->getSelected()) }}">
    <div>
        @foreach($field->getOptions() as $value => $label)
            <label class="block stack-xs custom-indicators" for="{{ $key.'-'.$value }}">
                <input v-on:click="changeType({{ $value }})" {{ old($key, $field->getSelected()) == $value ? 'checked="checked"':'' }}
                name="{{ $key }}"
                       value="{{ $value }}"
                       id="{{ $key.'-'.$value }}"
                       type="radio">
                <span class="custom-radiobutton"></span>
                <strong>{{ $label }}</strong>
            </label>
        @endforeach
    </div>
</radio-options>

<error class="caption text-warning" field="{{ $key }}" :errors="errors.all()"></error>
