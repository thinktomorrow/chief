@props([
    'activeTab' => null,
    'listenForExternalTab' => false,
    'showNav' => true,
])

<div
    {{ $attributes->class('space-y-3') }}
    x-cloak
    wire:ignore.self
    x-on:chieftab.window="listenForExternalTab"
    x-data="{
        activeTab: null,
        showNav: @js($showNav),

        init: function() {
            this.activeTab = @js($activeTab) || this.tabs()[0].id;
        },
        listenForExternalTab: function(e){
            if(!@js($listenForExternalTab)) return;

            if(this.activeTab == e.detail) return;

            this.activeTab = e.detail;
        },
        tabs: function() {
            const nodes = this.$refs.tabs.children;

            return Array.from(nodes).map((node) => ({
                'id': node.getAttribute('data-tab-id'),
                'label': node.getAttribute('data-tab-label').toLowerCase(),
            }));
        },
        showTab: function(id){
            this.activeTab = id
            this.$dispatch('chieftab', id);
        },
    }"
>
    <nav x-show="showNav" aria-label="Tabs" role="tablist" class="flex w-full gap-2 border-b border-grey-100">
        <template x-for="(tab,index) in tabs()">
            <a
                role="tab"
                x-on:click.prevent="showTab(tab.id)"
                x-html="tab.label"
                x-bind:aria-controls="tab.id"
                x-bind:aria-selected="tab.id === activeTab"
                class="block pb-1.5 cursor-pointer text-grey-600 with-bottomline px-1.5"
                x-bind:class="{ 'active': tab.id === activeTab }"
            ></a>
        </template>
    </nav>

    <div x-ref="tabs">
        {{ $slot }}
    </div>
</div>
