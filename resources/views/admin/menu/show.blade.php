<x-chief::page.template :title="ucfirst($menu->label())" container="md">
    <x-slot name="header">
        <x-chief::page.header
            :breadcrumbs="[
                ['label' => 'Dashboard', 'url' => route('chief.back.dashboard'), 'icon' => 'home'],
                ['label' => 'Menu', 'url' => route('chief.back.menus.index'), 'icon' => 'menu'],
                ucfirst($menu->label())
            ]"
        >
            <x-slot name="actions">
                <x-chief::button href="{{ route('chief.back.menuitem.create', $menu->key()) }}" variant="blue">
                    <x-chief::icon.plus-sign />
                    <span>Menu item toevoegen</span>
                </x-chief::button>
            </x-slot>
        </x-chief::page.header>
    </x-slot>

    <x-chief::window class="card">
        @if ($menuItems->isEmpty())
            <p class="body-dark">Momenteel zijn er nog geen menu items toegevoegd.</p>
        @else
            <div class="-my-3 divide-y divide-grey-100">
                @foreach ($menuItems as $item)
                    <x-chief::hierarchy
                        :item="$item"
                        view-path="chief::admin.menu._partials.menu-item"
                        iconMarginTop="0.2rem"
                    />
                @endforeach
            </div>
        @endif
    </x-chief::window>
</x-chief::page.template>
