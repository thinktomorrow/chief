<x-chief::page.template title="Menu overzicht" container="md">
    <x-slot name="header">
        <x-chief::page.header
            :breadcrumbs="[
                ['label' => 'Dashboard', 'url' => route('chief.back.dashboard'), 'icon' => 'home'],
                'Menu overzicht'
            ]"
        />
    </x-slot>

    <x-chief::window>
        <div class="-my-4 divide-y divide-grey-100">
            @foreach ($menus as $menu)
                <div class="flex items-center justify-between py-3">
                    <a
                        href="{{ route('chief.back.menus.show', $menu->key()) }}"
                        title="{{ ucfirst($menu->label()) }}"
                        class="body-dark font-medium hover:underline"
                    >
                        {{ ucfirst($menu->label()) }}
                    </a>

                    <x-chief::button
                        href="{{ route('chief.back.menus.show', $menu->key()) }}"
                        title="{{ ucfirst($menu->label()) }}"
                        size="sm"
                        variant="grey"
                    >
                        <x-chief::icon.quill-write />
                    </x-chief::button>
                </div>
            @endforeach
        </div>
    </x-chief::window>
</x-chief::page.template>
