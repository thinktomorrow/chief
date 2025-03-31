{{--
    reference: Reference of this tabs, context that is passed with the event for finetuning listeners
    wireIgnore:
    - true: wire:ignore - Needed for file inputs else they have their state removed ...
    - false: wire:ignore.self - Needed wire:modeling for everything else...
--}}

@props([
    'activeTab' => null,
    'listenForExternalTab' => false,
    'showNav' => true,
    'showTabs' => true,
    'dispatchTab' => true,
    'reference' => null,
    'size' => 'xs',
    'wireIgnore' => false,
])

<div
    x-cloak
    {{ $wireIgnore ? 'wire:ignore' : 'wire:ignore.self' }}
    data-slot="tabs"
    x-data="{
        activeTab: null,
        showNav: @js($showNav),
        showTabs: @js($showTabs),
        tabs: [],
        init: function () {
            this.tabs = Array.from(this.$refs.tabs.children).map((node) => ({
                'id': node.getAttribute('data-tab-id'),
                'label': node.getAttribute('data-tab-label'),
            }))

            this.activeTab =
                @js($activeTab) || (this.tabs.length > 0 ? this.tabs[0].id : null)

            this.repositionTabMarker()
        },
        listenForExternalTab: function (e) {
            if (! @js($listenForExternalTab)) return

            if (this.activeTab === e.detail.id) return

            // Check if this tabs accepts the given external tab
            this.tabs.forEach(({ id }) => {
                if (id === e.detail.id) {
                    this.activeTab = e.detail.id
                }
            })

            this.repositionTabMarker()
        },
        showTab: function (id) {
            this.activeTab = id

            if (! @js($dispatchTab)) return

            this.$dispatch('chieftab', { id: id, reference: '{{ $reference }}' })

            this.repositionTabMarker()
        },
        repositionTabMarker: function () {
            this.$nextTick(() => {
                const tabElement = Array.from(
                    this.$root.querySelectorAll(`[role='tablist'] [role='tab']`),
                ).find((tab) => tab.getAttribute('aria-selected') === 'true')

                if (! tabElement) return

                this.$refs.tabMarker.style.width = tabElement.offsetWidth + 'px'
                this.$refs.tabMarker.style.left = tabElement.offsetLeft + 'px'
            })
        },
    }"
    x-on:chieftab.window="listenForExternalTab"
    {{ $attributes }}
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
    <div
        x-show="showNav"
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
        <nav aria-label="Tabs" role="tablist" class="relative flex items-start justify-start border border-transparent">
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
                    type="button"
                    :key="tab.id"
                    role="tab"
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

    <div x-show="showTabs" x-ref="tabs">
        {{ $slot }}
    </div>
</div>
