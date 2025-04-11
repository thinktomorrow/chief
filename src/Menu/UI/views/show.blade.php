<x-chief::page.template :title="$typeLabel">
    <x-slot name="header">
        <x-chief::page.header
            :breadcrumbs="[
                ['label' => 'Menus', 'url' => route('chief.back.menus.index'), 'icon' => 'menu'],
                $typeLabel
            ]"
        />
    </x-slot>

    <livewire:chief-wire::menus :type="$type" :active-menu-id="$activeMenuId" />
    
</x-chief::page.template>
