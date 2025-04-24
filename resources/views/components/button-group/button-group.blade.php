@props([
    'activeTab' => null,
    'size' => 'base',
])

<div
    x-cloak
    x-data="buttonGroup()"
    x-on:click="
        (e) => {
            Array.from($refs.buttons.children).forEach((button) => {
                button.ariaSelected = e.target === button ? 'true' : 'false'
            })
            repositionTabMarker()
        }
    "
    {{ $attributes }}
>
    <div
        @class([
            'inline-block bg-grey-100',
            match ($size) {
                'xs' => 'rounded-[0.4375rem]',
                'sm' => 'rounded-[0.5625rem]',
                'base' => 'rounded-[0.6875rem]',
                default => 'rounded-[0.4375rem]',
            },
        ])
    >
        <nav x-ref="buttons" class="relative flex items-start justify-start border border-transparent">
            <div
                wire:ignore
                x-ref="tabMarker"
                @class([
                    'btn btn-outline-white absolute left-0 font-normal ring-0 transition-all duration-150 ease-out',
                    match ($size) {
                        'xs' => 'btn-xs px-2 text-sm/[1.125rem] *:h-[1.125rem]',
                        'sm' => 'btn-sm py-[0.3125rem] *:h-[1.125rem]',
                        'base' => 'btn-base py-[0.4375rem] *:h-5',
                        default => 'btn-xs px-2 text-sm/[1.125rem] *:h-[1.125rem]',
                    },
                ])
            >
                <span data-slot="tab-marker-content"></span>
            </div>

            {{ $slot }}
        </nav>
    </div>
</div>
