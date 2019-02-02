@if($field->prepend)
    <div class="input-addon">
        <div class="addon inset-s">{!! $field->prepend !!}</div>
        <input type="date" name="{{ $name ?? $key }}" id="{{ $key }}" class="input inset-s" placeholder="{{ $field->placeholder ?? '' }}" value="{{ old($key, optional($manager->getFieldValue($key))->format('Y-m-d')) }}">
    </div>
@elseif($field->append)
    <div class="input-addon">
        <input type="date" name="{{ $name ?? $key }}" id="{{ $key }}" class="input inset-s" placeholder="{{ $field->placeholder ?? '' }}" value="{{ old($key, optional($manager->getFieldValue($key))->format('Y-m-d')) }}">
        <div class="addon inset-s">{!! $field->append !!}</div>
    </div>
@else
    <input type="date" name="{{ $name ?? $key }}" id="{{ $key }}" class="input inset-s" placeholder="{{ $field->placeholder ?? '' }}" value="{{ old($key, optional($manager->getFieldValue($key))->format('Y-m-d')) }}">
@endif

<error class="caption text-warning" field="{{ $key }}" :errors="errors.all()"></error>