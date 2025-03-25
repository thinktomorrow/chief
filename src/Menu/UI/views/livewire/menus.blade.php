@php $menus = $this->getMenus(); @endphp

<div class="space-y-4">
    <div class="flex items-start justify-between gap-2">
        <nav aria-label="Tabs" role="tablist"
             class="flex items-start justify-start">
            @foreach($menus as $menu)
                <button
                    type="button"
                    role="tab"
                    wire:click.prevent="showMenu('{{ $menu->id }}')"
                    aria-controls="{{ $menu->id }}"
                    aria-selected="{{ $menu->id === $activeMenuId }}"
                    wire:key="menu-tabs-{{ $menu->id }}"
                    @class([
                        'bui-btn font-normal ring-0 transition-all duration-150 ease-out bui-btn-sm py-[0.3125rem] *:h-[1.125rem]',
                        'bui-btn-grey text-grey-950' => ($menu->id === $activeMenuId),
                        'text-grey-700 bui-btn-outline-white' => ($menu->id !== $activeMenuId),
                    ])
                >{{ $menu->title }}</button>

            @endforeach
        </nav>

        <x-chief::button wire:click="editMenus" variant="grey" size="xs">Aanpassen</x-chief::button>
    </div>

    @foreach ($menus as $menu)
        <div wire:key="menu-tab-content-{{ $menu->id }}">
            @if($menu->id === $activeMenuId)
                <livewire:chief-wire::table :key="'table-'.$menu->id" :table="$this->getMenuTable($menu->id)" />
            @endif
        </div>
    @endforeach

    <livewire:chief-wire::edit-menus :type="$type" />
</div>
