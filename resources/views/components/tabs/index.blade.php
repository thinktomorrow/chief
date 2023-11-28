@props([
    'activeTab' => null,
    'listenForExternalTab' => false,
    'showNav' => true,
    'showNavAsButtons' => false,
    'reference' => null, // Reference of this tabs, context that is passed with the event for finetuning listeners
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

            if(this.activeTab === e.detail.id) return;

            // Check if this tabs accepts the given external tab
            this.tabs().forEach(({id}) => {
                if(id === e.detail.id) {
                    this.activeTab = e.detail.id;
                }
            });
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
            this.$dispatch('chieftab', {id: id, reference: '{{ $reference }}'});
        },
    }"
>
    <nav x-show="showNav" aria-label="Tabs" role="tablist" class="flex w-full gap-2 border-b border-grey-100">
        <template x-for="(tab,index) in tabs()">
            <a
                x-on:click.prevent="showTab(tab.id)"
                x-html="tab.label"
                x-bind:aria-controls="tab.id"
                x-bind:aria-selected="tab.id === activeTab"
                role="tab"

                @if($showNavAsButtons)
                    class="text-grey-500 hover:text-grey-700 rounded-md px-3 py-2 text-sm font-medium cursor-pointer"
                    x-bind:class="{ 'bg-primary-100 text-primary-700': tab.id === activeTab }"
                @else
                    class="block pb-1.5 cursor-pointer text-grey-600 with-bottomline px-1.5"
                    x-bind:class="{ 'active': tab.id === activeTab }"
                @endif
            ></a>
        </template>
    </nav>

    <div x-ref="tabs">
        {{ $slot }}
    </div>
</div>
