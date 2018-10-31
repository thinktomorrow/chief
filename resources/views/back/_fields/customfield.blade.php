@if($field->type == \Thinktomorrow\Chief\Fields\Types\Types\FieldType::HTML)
<textarea data-editor class="inset-s" name="custom_fields[{{ $key }}]" id="custom_fields-{{ $key }}" cols="10" rows="5">{{ old('custom_fields.'.$locale.'.'. $key, $model->$key) }}</textarea>
@elseif($field->type == \Thinktomorrow\Chief\Fields\Types\Types\FieldType::SELECT)
<chief-multiselect
        name="custom_fields[{{ $key }}]"
        :options='@json($field->options)'
        selected='@json(old($key, $field->selected))'
        :multiple='@json(!!$field->multiple)'
>
</chief-multiselect>
@elseif($field->type == \Thinktomorrow\Chief\Fields\Types\Types\FieldType::RADIO)
    <radio-options inline-template :errors="errors" default-type="{{ old($key, $field->selected) }}">
        <div>
            @foreach($field->options as $value => $label)
                <label class="block stack-xs custom-indicators" for="{{ $key.'-'.$value }}">
                    <input v-on:click="changeType({{ $value }})" {{ old($key, $field->selected) == $value ? 'checked="checked"':'' }}
                    name="custom_fields[{{ $key }}]"
                           value="{{ $value }}"
                           id="{{ $key.'-'.$value }}"
                           type="radio">
                    <span class="custom-radiobutton --primary"></span>
                    <strong>{{ $label }}</strong>
                </label>
            @endforeach
        </div>
    </radio-options>
@elseif($field->type == \Thinktomorrow\Chief\Fields\Types\Types\FieldType::DATE)
<input type="date" class="input inset-s" id="custom_fields-{{ $key }}" name="custom_fields[{{ $key }}]" value="{{ old('custom_fields.' . $key, optional($model->$key)->format('Y-m-d\TH:i:s')) }}">
@else
    <input type="text" name="custom_fields[{{ $key }}]" id="custom_fields-{{ $key }}" class="input inset-s" placeholder="{{ $placeholder ?? '' }}" value="{{ old('custom_fields.' . $key, $model->$key) }}">
@endif

<error class="caption text-warning" field="custom_fields.{{ $key }}" :errors="errors.get('custom_fields')"></error>
