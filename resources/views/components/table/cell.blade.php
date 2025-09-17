@props([
    'justify' => 'start',
    'innerClass' => null,
])

<td
    {{
        $attributes->class([
            'text-left' => $justify === 'start',
            'text-center' => $justify === 'center',
            'text-right' => $justify === 'end',
        ])
    }}
>
    <div
        @class([
            'text-grey-500 flex min-h-6 items-center gap-1.5 leading-5',
            'justify-start' => $justify === 'start',
            'justify-center' => $justify === 'center',
            'justify-end' => $justify === 'end',
            $innerClass,
        ])
    >
        {{ $slot }}
    </div>
</td>
