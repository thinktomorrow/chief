<div>
    <div data-editor data-locale="{{ $locale ?? app()->getLocale() }}" class="inset-s bg-white" id="{{ $field->getName($locale ?? null) }}">
        {!! old($key, $field->getValue($locale ?? null)) !!}
    </div>

    <input name="{{ $field->getName($locale ?? null) }}" type="hidden" value="">

    <error class="caption text-warning" field="{{ $key }}" :errors="errors.all()"></error>
</div>

