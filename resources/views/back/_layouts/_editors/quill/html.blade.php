<div data-editor="{{ $key }}" class="inset-s bg-white" id="{{ $key }}">
    {!! old($key, $manager->fieldValue($field, $locale ?? null)) !!}
</div>

<input name="{{ $key }}" type="hidden" value="{{ old($key, $manager->fieldValue($field, $locale ?? null)) }}">

<error class="caption text-warning" field="{{ $key }}" :errors="errors.all()"></error>