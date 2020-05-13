@if($field->getAppend() && $field->getPrepend())
    <div class="input-addon">
        <div class="addon inset-s">{!! $field->getPrepend($locale ?? null) !!}</div>
        <input type="text" name="{{ $field->getName($locale ?? null) }}" id="{{ $field->getDottedName($locale ?? null) }}" class="input inset-s" placeholder="{{ $field->getPlaceholder($locale ?? null) }}" value="{{ old($field->getDottedName($locale ?? null), $field->getValue($locale ?? null)) }}">
        <div class="addon inset-s">{!! $field->getAppend($locale ?? null) !!}</div>
    </div>
@elseif($field->getPrepend())
    <div class="input-addon">
        <div class="addon inset-s">{!! $field->getPrepend($locale ?? null) !!}</div>
        <input type="text" name="{{ $field->getName($locale ?? null) }}" id="{{ $field->getDottedName($locale ?? null) }}" class="input inset-s" placeholder="{{ $field->getPlaceholder($locale ?? null) }}" value="{{ old($field->getDottedName($locale ?? null), $field->getValue($locale ?? null)) }}">
    </div>
@elseif($field->getAppend())
    <div class="input-addon">
        <input type="text" name="{{ $field->getName($locale ?? null) }}" id="{{ $field->getDottedName($locale ?? null) }}" class="input inset-s" placeholder="{{ $field->getPlaceholder($locale ?? null) }}" value="{{ old($field->getDottedName($locale ?? null),$field->getValue($locale ?? null)) }}">
        <div class="addon inset-s">{!! $field->getAppend($locale ?? null) !!}</div>
    </div>
@else
    <input type="text" name="{{ $field->getName($locale ?? null) }}" id="{{ $field->getDottedName($locale ?? null) }}" class="input inset-s" placeholder="{{ $field->getPlaceholder($locale ?? null) }}" value="{{ old($field->getDottedName($locale ?? null),$field->getValue($locale ?? null)) }}">
@endif
@if($field->hasCharacterCount())
    @include('chief::back._fields.charactercount')
@endif
{{--<error class="caption text-warning" field="{{ $field->getDottedName($locale ?? null) }}" :errors="errors.all()"></error>--}}
