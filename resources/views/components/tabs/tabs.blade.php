@props([
    'activeTab' => null,
    'dispatchTab' => true,
    'shouldListenForExternalTab' => false,
    'reference' => null,
    'showNav' => true,
    'showTabs' => true,
    'size' => 'xs',
    'wireIgnore' => false,
    'actions' => [],
])

{{--
    reference: Reference of this tabs, context that is passed with the event for finetuning listeners
    wireIgnore:
    - true: wire:ignore - Needed for file inputs else they have their state removed ...
    - false: wire:ignore.self - Needed wire:modeling for everything else...
--}}

<div
    x-cloak
    x-data="tabs({
                activeTab: @js($activeTab),
                dispatchTab: @js($dispatchTab),
                shouldListenForExternalTab: @js($shouldListenForExternalTab),
                reference: @js($reference),
                showNav: @js($showNav),
                showTabs: @js($showTabs),
            })"
    {{
        $attributes->merge([
            'data-slot' => 'tabs',
            'wire:ignore' => $wireIgnore,
            'wire:ignore.self' => ! $wireIgnore,
        ])
    }}
    :class="{
        '{{
            match ($size) {
                'xs' => 'space-y-2',
                'sm' => 'space-y-3',
                'base' => 'space-y-4',
                default => 'space-y-2',
            }
        }}': showNav && showTabs
    }"
>
    <div x-show="showNav" class="flex items-start justify-between gap-2">
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
            <nav
                aria-label="Tabs"
                role="tablist"
                class="relative flex items-start justify-start border border-transparent"
            >
                <div
                    x-ref="tabMarker"
                    x-show="activeTab"
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

                <template x-for="(tab, index) in tabs" :key="tab.id">
                    <button
                        :key="tab.id"
                        type="button"
                        role="tab"
                        tabindex="-1"
                        x-on:click.prevent="showTab(tab.id)"
                        x-html="tab.label"
                        x-bind:aria-controls="tab.id"
                        x-bind:aria-selected="tab.id === activeTab"
                        @class([
                            'btn relative font-normal shadow-none',
                            match ($size) {
                                'xs' => 'btn-xs px-2 text-sm/[1.125rem]',
                                'sm' => 'btn-sm py-[0.3125rem]',
                                'base' => 'btn-base py-[0.4375rem]',
                                default => 'btn-xs px-2 text-sm/[1.125rem]',
                            },
                        ])
                        :class="{
                        'text-grey-950': tab.id === activeTab,
                        'text-grey-700': tab.id !== activeTab,
                    }"
                    ></button>
                </template>
            </nav>
        </div>

        @if ($actions)
            {{ $actions }}
        @endif
    </div>

    <div x-show="showTabs" x-ref="tabs">
        {{ $slot }}
    </div>
</div>
