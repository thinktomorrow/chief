@props(['active' => false])

<button
    {{
        $attributes->class(['group relative'])->merge([
            'type' => 'button',
            'role' => 'tab',
            'data-slot' => $active ? 'active-tab' : 'inactive-tab',
        ])
    }}
>
    @if ($active)
        <div class="absolute inset-0 z-[-1] rounded-t-xl border-x border-t border-grey-100 bg-white shadow-md"></div>
    @endif

    <div
        data-slot="tab-label"
        @class([
            'relative border-x border-t px-2',
            'rounded-t-xl border-grey-100 bg-white py-1.5' => $active,
            'my-1.5 border-transparent text-grey-500' => ! $active,
        ])
    >
        <div
            @class([
                'text-nowrap px-2 py-1.5 text-base/5',
                'text-grey-950' => $active,
                'rounded-[0.625rem] text-grey-500 group-hover:bg-grey-100 group-hover:text-grey-700' => ! $active,
            ])
        >
            {{ $slot }}
        </div>
    </div>
</button>
