{{-- "reference" => Reference of this tabs, context that is passed with the event for finetuning listeners --}}

@props([
    'activeTab' => null,
    'listenForExternalTab' => false,
    'showNav' => true,
    'dispatchTab' => true,
    'reference' => null,
    'size' => 'xs',
])

<div
    x-cloak
    data-slot="tabs"
    wire:key="tabs-index-{{ \Illuminate\Support\Str::random() }}"
    x-on:chieftab.window="listenForExternalTab"
    x-data="{
        activeTab: null,
        showNav: @js($showNav),
        init: function () {
            this.activeTab =
                @js($activeTab) || (this.tabs().length > 0 ? this.tabs()[0].id : null)

            this.repositionTabMarker()
        },
        listenForExternalTab: function (e) {
            if (! @js($listenForExternalTab)) return

            if (this.activeTab === e.detail.id) return

            // Check if this tabs accepts the given external tab
            this.tabs().forEach(({ id }) => {
                if (id === e.detail.id) {
                    this.activeTab = e.detail.id
                }
            })

            this.repositionTabMarker()
        },
        tabs: function () {
            const nodes = this.$refs.tabs.children

            return Array.from(nodes).map((node) => ({
                'id': node.getAttribute('data-tab-id'),
                'label': node.getAttribute('data-tab-label'),
            }))
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

                this.$refs.tabMarker.style.width = tabElement.offsetWidth + 'px'
                this.$refs.tabMarker.style.left = tabElement.offsetLeft + 'px'
            })
        },
    }"
    {{ $attributes }}
>
    <div
        x-show="showNav"
        @class([
            'mb-2 inline-block bg-grey-100',
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
                    'bui-btn bui-btn-outline-white absolute left-0 ring-0 transition-all duration-150 ease-out',
                    match ($size) {
                        'xs' => 'bui-btn-xs px-2 text-sm/[1.125rem] *:h-[1.125rem]',
                        'sm' => 'bui-btn-sm py-[0.3125rem] *:h-[1.125rem]',
                        'base' => 'bui-btn-base py-[0.4375rem] *:h-5',
                        default => 'bui-btn-xs px-2 text-sm/[1.125rem] *:h-[1.125rem]',
                    },
                ])
            >
                <span data-slot="tab-marker-content"></span>
            </div>

            <template x-for="(tab, index) in tabs()">
                <button
                    type="button"
                    :key="tab.id"
                    role="tab"
                    x-on:click.prevent="showTab(tab.id)"
                    x-html="tab.label"
                    x-bind:aria-controls="tab.id"
                    x-bind:aria-selected="tab.id === activeTab"
                    @class([
                        'bui-btn relative shadow-none',
                        match ($size) {
                            'xs' => 'bui-btn-xs px-2 text-sm/[1.125rem]',
                            'sm' => 'bui-btn-sm py-[0.3125rem]',
                            'base' => 'bui-btn-base py-[0.4375rem]',
                            default => 'bui-btn-xs px-2 text-sm/[1.125rem]',
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

    <div x-ref="tabs">
        {{ $slot }}
    </div>
</div>
