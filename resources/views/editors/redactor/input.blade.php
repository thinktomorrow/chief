<textarea {{ $attributes->merge($getCustomAttributes())->merge([
        'cols' => '5',
        'rows' => '5',
        'style' => 'resize: vertical',
        'v-pre' => 'v-pre',
    ])->class([
        'w-full',
    ]) }}
        data-editor
        data-locale="{{ $locale ?? app()->getLocale() }}"
        name="{{ $getName($locale ?? null) }}"
        data-custom-redactor-options='@json($getRedactorOptions($getId($locale ?? null), $locale ?? app()->getLocale()))'
        id="{{ $getId($locale ?? null) }}"
>{{ old($getId($locale ?? null), $getValue($locale ?? null)) }}</textarea>
