@foreach($model->translatableFields() as $key => $field)
    <div class="stack">
        @if($field->label)
            <label for="trans-{{ $locale }}-{{ $key }}">{{ $field->label }}</label>
        @endif

        @if($field->type == \Thinktomorrow\Chief\Common\TranslatableFields\FieldType::HTML)
            <textarea data-editor class="inset-s" name="trans[{{ $locale }}][{{ $key }}]" id="trans-{{ $locale }}-{{ $key }}" cols="10" rows="5">{{ old('trans.'.$locale.'.'. $key,$model->translateForForm($locale,$key)) }}</textarea>
        @elseif($field->type == \Thinktomorrow\Chief\Common\TranslatableFields\FieldType::DATE)
            <input type="datetime-local" class="input inset-s" name="{{$key}}" value="{{ old($key, $model->$key) }}">
        @else
            <input type="text" name="trans[{{ $locale }}][{{ $key }}]" id="trans-{{ $locale }}-{{ $key }}" class="input inset-s" placeholder="{{ $placeholder ?? '' }}" value="{{ old('trans.'.$locale.'.'.$key, $model->translateForForm($locale,$key)) }}">
        @endif
        @if($field->description)
            <p>{{ $field->description }}</p>
        @endif
        <error class="caption text-warning" field="trans.{{ $locale }}.{{ $key }}" :errors="errors.get('trans.{{ $locale }}')"></error>
    </div>
@endforeach
