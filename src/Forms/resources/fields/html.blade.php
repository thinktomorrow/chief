<textarea {{ $attributes->merge($getCustomAttributes())->merge([
        'cols' => '10',
        'rows' => '5',
        'style' => 'resize: vertical',
        'v-pre' => 'v-pre',
    ])->class([
        'w-full',
    ]) }}
          data-editor
          data-locale="{{ $locale ?? app()->getLocale() }}"
          name="{{ $getName($locale ?? null) }}"
          data-custom-redactor-options='@json($getRedactorOptions($locale ?? null))'
          id="{{ $getId($locale ?? null) }}"
>{{ $getActiveValue($locale ?? null) }}</textarea>
