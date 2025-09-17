@props([
    'justify' => 'start',
    'innerClass' => null,
])

<th
    scope="col"
    {{
        $attributes->class([
            'text-left' => $justify === 'start',
            'text-center' => $justify === 'center',
            'text-right' => $justify === 'end',
        ])
    }}
>
    <span @class(['text-grey-700 text-xs/5 font-medium tracking-wider uppercase', $innerClass])>
        {{ $slot }}
    </span>
</th>
