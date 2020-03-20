@if($field->getAppend() && $field->getPrepend())
    <div class="input-addon">
        <div class="addon inset-s">{!! $field->getPrepend($locale ?? null) !!}</div>
        <input type="text" name="{{ $name ?? $key }}" id="{{ $key }}" class="input inset-s" placeholder="{{ $field->getPlaceholder($locale ?? null) }}" value="{{ old($key, $field->getValue($locale ?? null)) }}">
        <div class="addon inset-s">{!! $field->getAppend($locale ?? null) !!}</div>
    </div>
@elseif($field->getPrepend())
    <div class="input-addon">
        <div class="addon inset-s">{!! $field->getPrepend($locale ?? null) !!}</div>
        <input type="text" name="{{ $name ?? $key }}" id="{{ $key }}" class="input inset-s" placeholder="{{ $field->getPlaceholder($locale ?? null) }}" value="{{ old($key, $field->getValue($locale ?? null)) }}">
    </div>
@elseif($field->getAppend())
    <div class="input-addon">
        <input type="text" name="{{ $name ?? $key }}" id="{{ $key }}" class="input inset-s" placeholder="{{ $field->getPlaceholder($locale ?? null) }}" value="{{ old($key,$field->getValue($locale ?? null)) }}">
        <div class="addon inset-s">{!! $field->getAppend($locale ?? null) !!}</div>
    </div>
@else
    <input type="text" name="{{ $name ?? $key }}" id="{{ $key }}" class="input inset-s" placeholder="{{ $field->getPlaceholder($locale ?? null) }}" value="{{ old($key,$field->getValue($locale ?? null)) }}">
@endif

<error class="caption text-warning" field="{{ $key }}" :errors="errors.all()"></error>
