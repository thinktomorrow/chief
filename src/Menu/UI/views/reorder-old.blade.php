<x-chief::page.template title="Herschikken">
    <x-slot name="header">
        <x-chief::page.header
            :breadcrumbs="[
                ['label' => 'Menu', 'url' => route('chief.back.menus.index'), 'icon' => 'menu'],
                ['label' => $menu->getTitle(), 'url' => route('chief.back.menus.show', [$menu->type, $menu->id]), 'icon' => 'menu'],
                'Herschikken'
            ]"
        />
    </x-slot>

    <x-chief::window class="card">
        <div
            data-sortable
            data-sortable-is-sorting
            data-sortable-endpoint="{{ route('chief.back.menus.reorder.update', $menu->id) }}"
            class="gutter-1 flex flex-wrap items-stretch justify-start"
        >
            @foreach ($menuItems as $menuItem)
                <div
                    data-sortable-handle
                    data-sortable-id="{{ $menuItem->getKey() }}"
                    class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4"
                >
                    <div
                        class="border-grey-100 bg-grey-50 hover:bg-grey-100 h-full cursor-move rounded-md border p-3 transition duration-75 ease-in-out"
                    >
                        <p class="h6 h1-dark text-sm">
                            {{ $menuItem->getLabel() }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </x-chief::window>

    <x-slot name="sidebar">
        <x-chief::window class="card">
            <div class="space-y-4">
                <x-chief::button href="{{ route('chief.back.menus.show', [$menu->type, $menu->id]) }}" variant="blue">
                    Stop met herschikken
                </x-chief::button>

                <p class="body-dark body text-sm">
                    Sleep de blokken in de gewenste volgorde. De volgorde wordt automatisch bewaard.
                </p>
            </div>
        </x-chief::window>
    </x-slot>
</x-chief::page.template>
