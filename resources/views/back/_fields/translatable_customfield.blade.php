@if($field->type == \Thinktomorrow\Chief\Fields\Types\Types\FieldType::HTML)
    <textarea data-editor class="inset-s" name="trans[{{ $locale }}][{{ $key }}]" id="trans-{{ $locale }}-{{ $key }}" cols="10" rows="5">{{ old('trans.'.$locale.'.'. $key,$model->translateForForm($locale,$key)) }}</textarea>
@else
    <input type="text" name="trans[{{ $locale }}][{{ $key }}]" id="trans-{{ $locale }}-{{ $key }}" class="input inset-s" placeholder="{{ $placeholder ?? '' }}" value="{{ old('trans.'.$locale.'.'.$key, $model->translateForForm($locale,$key)) }}">
@endif
<error class="caption text-warning" field="trans.{{ $locale }}.{{ $key }}" :errors="errors.get('trans.{{ $locale }}')"></error>
