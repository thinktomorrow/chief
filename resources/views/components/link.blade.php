@props([
    'underline'
])

<span {{
    $attributes->class([
        'inline-flex items-start text-sm leading-5 gap-1.5 transition-all duration-75 ease-in-out',
        'text-grey-700 hover:text-primary-500',
        '[&>svg]:shrink-0 [&>svg]:w-5 [&>svg]:h-5 [&>svg]:transition-all [&>svg]:duration-75 [&>svg]:ease-in-out [&:hover>svg]:scale-110',
        'underline underline-offset-2' => isset($underline),
    ])
}}>
    {{ $slot }}
</span>
