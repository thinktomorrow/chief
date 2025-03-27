<x-chief::page.template title="Menu item bewerken" container="md">
    <x-slot name="header">
        <x-chief::page.header
            :breadcrumbs="[
                ['label' => 'Dashboard', 'url' => route('chief.back.dashboard'), 'icon' => 'home'],
                ['label' => 'Menus', 'url' => route('chief.back.menus.index'), 'icon' => 'menu'],
                ['label' => $menu->getTitle(), 'url' => route('chief.back.menus.show', [$menu->type, $menu->id]), 'icon' => 'menu'],
                'Menu item bewerken'
            ]"
        >
            <x-slot name="actions">
                <x-chief::button
                    x-data
                    x-on:click="$dispatch('open-dialog', { 'id': 'delete-menuitem-{{ $menuitem->id }}'})"
                    variant="outline-red"
                >
                    Verwijder
                </x-chief::button>

                <x-chief::button form="updateForm" type="submit" variant="blue">Bewaar</x-chief::button>
            </x-slot>
        </x-chief::page.header>
    </x-slot>

    <x-chief::window>
        <form
            id="updateForm"
            method="POST"
            action="{{ route('chief.back.menuitem.update', $menuitem->id) }}"
            enctype="multipart/form-data"
            role="form"
        >
            @csrf
            @method('put')

            @include('chief-menu::_partials.form')
        </form>
    </x-chief::window>

    @include('chief-menu::_partials.delete-modal')
</x-chief::page.template>
