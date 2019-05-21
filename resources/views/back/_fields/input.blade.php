@if($field->prepend)
    <div class="input-addon">
        <div class="addon inset-s">{!! $field->translateValue($field->prepend, $locale ?? null) !!}</div>
        <input type="text" name="{{ $name ?? $key }}" id="{{ $key }}" class="input inset-s" placeholder="{{ $field->placeholder ?? '' }}" value="{{ old($key, $manager->getFieldValue($key)) }}">
    </div>
@elseif($field->append)
    <div class="input-addon">
        <input type="text" name="{{ $name ?? $key }}" id="{{ $key }}" class="input inset-s" placeholder="{{ $field->placeholder ?? '' }}" value="{{ old($key, $manager->getFieldValue($key)) }}">
        <div class="addon inset-s">{!! $field->translateValue($field->append, $locale ?? null) !!}</div>
    </div>
@else
    <input type="text" name="{{ $name ?? $key }}" id="{{ $key }}" class="input inset-s" placeholder="{{ $field->placeholder ?? '' }}" value="{{ old($key, $manager->getFieldValue($key)) }}">
@endif

<error class="caption text-warning" field="{{ $key }}" :errors="errors.all()"></error>
