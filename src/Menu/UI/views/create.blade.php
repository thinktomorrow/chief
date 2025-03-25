<x-chief::page.template title="Menu item toevoegen" container="md">
    <x-slot name="header">
        <x-chief::page.header
            :breadcrumbs="[
                ['label' => 'Dashboard', 'url' => route('chief.back.dashboard'), 'icon' => 'home'],
                ['label' => 'Menu', 'url' => route('chief.back.menus.index'), 'icon' => 'menu'],
                ['label' => $menu->getTitle(), 'url' => route('chief.back.menus.show', [$menu->type, $menu->id]), 'icon' => 'menu'],
                'Menu item toevoegen'
            ]"
        >
            <x-slot name="actions">
                <x-chief::button form="createForm" type="submit" variant="blue">Voeg menu item toe</x-chief::button>
            </x-slot>
        </x-chief::page.header>
    </x-slot>

    <x-chief::window class="card">
        <form
            id="createForm"
            method="POST"
            action="{{ route('chief.back.menuitem.store', $menu->id) }}"
            enctype="multipart/form-data"
            role="form"
        >
            @csrf

            <input type="hidden" name="menu_type" value="{{ $menuitem->menu_type }}" />

            @include('chief-menu::_partials.form')
        </form>
    </x-chief::window>
</x-chief::page.template>
