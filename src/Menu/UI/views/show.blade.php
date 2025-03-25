<x-chief::page.template :title="$typeLabel" container="md">
    <x-slot name="header">
        <x-chief::page.header
            :breadcrumbs="[
                ['label' => 'Dashboard', 'url' => route('chief.back.dashboard'), 'icon' => 'home'],
                ['label' => 'Menu', 'url' => route('chief.back.menus.index'), 'icon' => 'menu'],
                $typeLabel
            ]"
        />
    </x-slot>

    <livewire:chief-wire::menus :type="$type" :active-menu-id="$activeMenuId" />

</x-chief::page.template>

