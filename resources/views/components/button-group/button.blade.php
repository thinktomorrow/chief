@props([
    'active' => false,
])

@aware(['size'])

<button
    type="button"
    role="tab"
    tabindex="-1"
    @class([
        'btn relative font-normal shadow-none',
        match ($size) {
            'xs' => 'btn-xs px-2 text-sm/[1.125rem]',
            'sm' => 'btn-sm py-[0.3125rem]',
            'base' => 'btn-base py-[0.4375rem]',
            default => 'btn-xs px-2 text-sm/[1.125rem]',
        },
        match ($active) {
            true => 'text-grey-950',
            false => 'text-grey-700',
        },
    ])
    {{ $attributes }}
>
    {{ $slot }}
</button>
