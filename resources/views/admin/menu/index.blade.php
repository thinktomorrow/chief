<x-chief::template title="Menu overzicht">
    <x-slot name="hero">
        <x-chief::template.hero title="Menu overzicht" class="max-w-3xl"/>
    </x-slot>

    <x-chief::template.grid class="max-w-3xl">
        <div class="card">
            <div class="-my-3 divide-y divide-grey-100">
                @foreach($menus as $menu)
                    <div class="flex items-center justify-between py-3">
                        <a
                            href="{{ route('chief.back.menus.show', $menu->key()) }}"
                            title="{{ ucfirst($menu->label()) }}"
                            class="font-medium body-dark hover:underline"
                        >
                            {{ ucfirst($menu->label()) }}
                        </a>

                        <a
                            href="{{ route('chief.back.menus.show', $menu->key()) }}"
                            title="{{ ucfirst($menu->label()) }}"
                            class="shrink-0 link link-primary"
                        >
                            <x-chief-icon-button icon="icon-edit"/>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </x-chief::template.grid>
</x-chief::template>
