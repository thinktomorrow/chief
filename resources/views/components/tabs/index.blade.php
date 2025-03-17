{{-- "reference" => Reference of this tabs, context that is passed with the event for finetuning listeners --}}

@props([
    "activeTab" => null,
    "listenForExternalTab" => false,
    "showNav" => true,
    "showNavAsButtons" => false,
    "dispatchTab" => true,
    "reference" => null,
])

<div
    x-cloak
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
                'label': node.getAttribute('data-tab-label').toLowerCase(),
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
    <div x-show="showNav" class="mb-2 inline-block rounded-[0.5rem] bg-grey-100">
        <nav aria-label="Tabs" role="tablist" class="relative flex items-start justify-start border border-transparent">
            <div
                x-ref="tabMarker"
                x-show="activeTab"
                class="bui-btn bui-btn-xs bui-btn-outline-white absolute left-0 rounded-[0.4375rem] px-2 text-sm/5 ring-0 transition-all duration-150 ease-out"
            >
                <span class="h-5"></span>
            </div>

            <template x-for="(tab, index) in tabs()">
                <button
                    type="button"
                    :key="tab.id"
                    role="tab"
                    x-on:click.prevent="showTab(tab.id)"
                    {{-- TODO(tijs): wrong in so many ways --}}
                    x-html="tab.label"
                    x-bind:aria-controls="tab.id"
                    x-bind:aria-selected="tab.id === activeTab"
                    @if ($showNavAsButtons)
                        class="bui-btn bui-btn-xs relative cursor-pointer px-2 text-sm/5 shadow-none"
                        x-bind:class="{ 'bg-primary-100 text-primary-700': tab.id === activeTab }"
                    @else
                        class="block pb-1.5 cursor-pointer text-grey-600 with-bottomline px-1.5"
                        x-bind:class="{ 'active': tab.id === activeTab }"
                    @endif
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
