<x-chief::page.template title="Site menus" container="md">
    <x-slot name="header">
        <x-chief::page.header
            :breadcrumbs="[
                ['label' => 'Dashboard', 'url' => route('chief.back.dashboard'), 'icon' => 'home'],
                'Menus'
            ]"
        />
    </x-slot>

    <x-chief::window>
        <div class="-my-4 divide-y divide-grey-100">
            @foreach ($menuTypes as $menuType)
                <div class="flex items-center justify-between py-3">
                    <a
                        href="{{ route('chief.back.menus.show', $menuType->getType()) }}"
                        title="{{ ucfirst($menuType->getLabel()) }}"
                        class="body-dark font-medium hover:underline"
                    >
                        {{ ucfirst($menuType->getLabel()) }}
                    </a>

                    <x-chief::button
                        href="{{ route('chief.back.menus.show', $menuType->getType()) }}"
                        title="{{ ucfirst($menuType->getLabel()) }}"
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
