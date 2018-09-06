@if($field->type == \Thinktomorrow\Chief\Common\TranslatableFields\FieldType::HTML)
<textarea data-editor class="inset-s" name="custom_fields[{{ $key }}]" id="custom_fields-{{ $key }}" cols="10" rows="5">{{ old('custom_fields.'.$locale.'.'. $key, $model->$key) }}</textarea>
@elseif($field->type == \Thinktomorrow\Chief\Common\TranslatableFields\FieldType::SELECT)
<chief-multiselect
        name="custom_fields[{{ $key }}]"
        :options='@json($field->options)'
        selected='@json(old($key, $field->selected))'
        :multiple='@json(!!$field->multiple)'
>
</chief-multiselect>
@elseif($field->type == \Thinktomorrow\Chief\Common\TranslatableFields\FieldType::DATE)
<input type="date" class="input inset-s" id="custom_fields-{{ $key }}" name="custom_fields[{{ $key }}]" value="{{ old('custom_fields.' . $key, optional($model->$key)->format('Y-m-d\TH:i:s')) }}">
@else
<input type="text" name="custom_fields[{{ $key }}]" id="custom_fields-{{ $key }}" class="input inset-s" placeholder="{{ $placeholder ?? '' }}" value="{{ old('custom_fields.' . $key, $model->$key) }}">
@endif

<error class="caption text-warning" field="custom_fields.{{ $key }}" :errors="errors.get('custom_fields')"></error>
