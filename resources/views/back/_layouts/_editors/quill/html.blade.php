<div data-editor data-locale="{{ $locale ?? app()->getLocale() }}" class="inset-s bg-white" id="{{ $name ?? $key }}">
    {!! old($key, $manager->fieldValue($field, $locale ?? null)) !!}
</div>

<input name="{{ $name ?? $key }}" type="hidden" value="">

<error class="caption text-warning" field="{{ $key }}" :errors="errors.all()"></error>