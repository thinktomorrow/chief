@props([
    'underline',
])

<span
    {{
        $attributes->class([
            'inline-flex items-start gap-1.5 text-sm leading-5 transition-all duration-75 ease-in-out',
            'text-grey-700 hover:text-primary-500',
            '[&:hover>svg]:scale-110 [&>svg]:h-5 [&>svg]:w-5 [&>svg]:shrink-0 [&>svg]:transition-all [&>svg]:duration-75 [&>svg]:ease-in-out',
            'underline underline-offset-2' => isset($underline),
        ])
    }}
>
    {{ $slot }}
</span>
