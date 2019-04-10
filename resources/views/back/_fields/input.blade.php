@if($field->prepend)
    <div class="input-addon">
        <div class="addon inset-s">{!! isset($locale) ? $field->translateValue($field->prepend, $locale) : $field->prepend !!}</div>
        <input type="text" name="{{ $name ?? $key }}" id="{{ $key }}" class="input inset-s" placeholder="{{ $field->placeholder ?? '' }}" value="{{ old($key, $manager->getFieldValue($key)) }}">
    </div>
@elseif($field->append)
    <div class="input-addon">
        <input type="text" name="{{ $name ?? $key }}" id="{{ $key }}" class="input inset-s" placeholder="{{ $field->placeholder ?? '' }}" value="{{ old($key, $manager->getFieldValue($key)) }}">
        <div class="addon inset-s">{!! isset($locale) ? $field->translateValue($field->append, $locale) : $field->append !!}</div>
    </div>
@else
    <input type="text" name="{{ $name ?? $key }}" id="{{ $key }}" class="input inset-s" placeholder="{{ $field->placeholder ?? '' }}" value="{{ old($key, $manager->getFieldValue($key)) }}">
@endif

<error class="caption text-warning" field="{{ $key }}" :errors="errors.all()"></error>
