<textarea {{ $attributes->merge($getCustomAttributes())->merge([
        'cols' => '5',
        'rows' => '5',
        'style' => 'resize: vertical',
        'v-pre' => 'v-pre',
    ])->class([
        'w-full',
    ]) }}
    name="{{ $getName($locale ?? null) }}"
    id="{{ $getId($locale ?? null) }}"
>{{ $getActiveValue($locale ?? null) }}</textarea>
