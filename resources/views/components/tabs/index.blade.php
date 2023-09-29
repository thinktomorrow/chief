@props([
    'activeTab' => null,
    'listenForExternalTab' => false,
    'showNav' => true,
])

<div x-cloak
     wire:ignore.self
     x-on:chieftab.window="listenForExternalTab"
     x-data="{

        init: function(){
            this.activeTab = @js($activeTab) || this.tabs()[0].id;
        },

        listenForExternalTab: function(e){
            if(!@js($listenForExternalTab)) return;

            if(this.activeTab == e.detail) return;

            this.activeTab = e.detail;
        },

        activeTab: null,

        showNav: @js($showNav),

        tabs: function(){
            const nodes = this.$refs.tabs.children;

            return Array.from(nodes).map((node) => ({
                'id': node.getAttribute('data-tab-id'),
                'label': node.getAttribute('data-tab-label'),
            }));
        },

        showTab: function(id){
            this.activeTab = id
            this.$dispatch('chieftab', id);
        },

     }"
        {{ $attributes->class('space-y-3') }}>
    <nav x-show="showNav" aria-label="Tabs" role="tablist" class="flex w-full pl-0 border-b border-grey-100">
        <template x-for="(tab,index) in tabs()">
            <a
                    x-on:click.prevent="showTab(tab.id)"
                    x-html="tab.label"
                    x-bind:aria-controls="tab.id"
                    x-bind:aria-selected="tab.id === activeTab"
                    role="tab"
                    {{--                class="text-grey-500 hover:text-grey-700 rounded-md px-3 py-2 text-sm font-medium"--}}
                    class="block px-3 pb-2 text-grey-600 with-bottomline cursor-pointer"
                    {{--                x-bind:class="{ 'bg-primary-100 text-primary-700': tab.id === activeTab }"--}}
                    x-bind:class="{ 'active': tab.id === activeTab }"
            ></a>
        </template>
    </nav>

    <div x-ref="tabs">
        {{ $slot }}
    </div>
</div>
