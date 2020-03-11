@if($field->getPrepend())
    <div class="input-addon">
        <div class="addon inset-s">{!! $field->getPrepend($locale ?? null) !!}</div>
        <input max="3000-01-01" type="date" name="{{ $name ?? $key }}" id="{{ $key }}" class="input inset-s" placeholder="{{ $field->getPlaceholder($locale ?? null) }}" value="{{ old($key, optional($manager->fieldValue($field, $locale ?? null))->format('Y-m-d')) }}">
    </div>
@elseif($field->getAppend())
    <div class="input-addon">
        <input max="3000-01-01" type="date" name="{{ $name ?? $key }}" id="{{ $key }}" class="input inset-s" placeholder="{{ $field->getPlaceholder($locale ?? null) }}" value="{{ old($key, optional($manager->fieldValue($field, $locale ?? null))->format('Y-m-d')) }}">
        <div class="addon inset-s">{!! $field->getAppend($locale ?? null) !!}</div>
    </div>
@else
    <input max="3000-01-01" type="date" name="{{ $name ?? $key }}" id="{{ $key }}" class="input inset-s" placeholder="{{ $field->getPlaceholder($locale ?? null) }}" value="{{ old($key, optional($manager->fieldValue($field, $locale ?? null))->format('Y-m-d')) }}" >
@endif

<error class="caption text-warning" field="{{ $key }}" :errors="errors.all()"></error>
