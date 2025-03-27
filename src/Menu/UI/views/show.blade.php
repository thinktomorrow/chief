<x-chief::page.template :title="$typeLabel">
    <x-slot name="header">
        <x-chief::page.header
            :breadcrumbs="[
                ['label' => 'Menu', 'url' => route('chief.back.menus.index'), 'icon' => 'menu'],
                $typeLabel
            ]"
        />
    </x-slot>

    <livewire:chief-wire::menus :type="$type" :active-menu-id="$activeMenuId" />

    <x-slot name="sidebar">
        <livewire:chief-wire::menu-sites :type="$type" />
    </x-slot>
</x-chief::page.template>
