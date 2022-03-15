<textarea
    data-editor
    data-locale="{{ $locale ?? app()->getLocale() }}"
    data-custom-redactor-options='@json($getRedactorOptions($locale ?? null))'
    id="{{ $getId($locale ?? null) }}"
    name="{{ $getName($locale ?? null) }}"
    {{ $attributes->merge($getCustomAttributes())->merge([
        'cols' => '10',
        'rows' => '5',
        'style' => 'resize: vertical',
        'v-pre' => 'v-pre',
    ])->class([
        'w-full',
    ]) }}
>{{ $getActiveValue($locale ?? null) }}</textarea>
