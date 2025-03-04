@props([
    'activeTab' => null,
    'listenForExternalTab' => false,
    'showNav' => true,
])

<div
    x-cloak
    wire:ignore
    x-on:chieftab.window="listenForExternalTab"
    x-data="{
        activeTab: null,
        showNav: @js($showNav),

        init: function () {
            this.activeTab = @js($activeTab) || this.tabs()[0].id

            this.repositionTabMarker()
        },
        listenForExternalTab: function (e) {
            if (! @js($listenForExternalTab)) return

            if (this.activeTab == e.detail) return

            this.activeTab = e.detail

            this.repositionTabMarker()
        },
        tabs: function () {
            const nodes = this.$refs.tabs.children

            return Array.from(nodes).map((node) => ({
                'id': node.getAttribute('data-tab-id'),
                'label': node.getAttribute('data-tab-label').toLowerCase(),
            }))
        },
        showTab: function (id) {
            this.activeTab = id
            this.$dispatch('chieftab', id)

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
    {{ $attributes->class('space-y-2') }}
>
    <div class="inline-block rounded-[0.5rem] bg-grey-100">
        <nav
            x-show="showNav"
            aria-label="Tabs"
            role="tablist"
            class="relative flex items-start justify-start border border-transparent"
        >
            <div
                x-ref="tabMarker"
                x-show="activeTab"
                class="bui-btn bui-btn-xs bui-btn-outline-white absolute left-0 px-2 text-sm/5 ring-0 transition-all duration-150 ease-out"
            >
                <span class="h-5"></span>
            </div>

            <template x-for="(tab, index) in tabs()">
                <a
                    :key="tab.id"
                    role="tab"
                    x-on:click.prevent="showTab(tab.id)"
                    {{-- TODO: wrong in so many ways --}}
                    x-html="tab.label == 'nl' ? 'Nederlands' : 'Frans'"
                    x-bind:aria-controls="tab.id"
                    x-bind:aria-selected="tab.id === activeTab"
                    class="bui-btn bui-btn-xs relative cursor-pointer px-2 text-sm/5 text-grey-800 shadow-none peer-checked:text-grey-950"
                ></a>
            </template>
        </nav>
    </div>

    <div x-ref="tabs">
        {{ $slot }}
    </div>
</div>
