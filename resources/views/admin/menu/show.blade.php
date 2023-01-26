@php
    $title = ucfirst($menu->label());
    $breadcrumb = null;

    if(\Thinktomorrow\Chief\Site\Menu\Menu::all()->count() > 1) {
        $breadcrumb = new \Thinktomorrow\Chief\Admin\Nav\Breadcrumb('Terug naar menu overzicht', route('chief.back.menus.index'));
    }
@endphp

<x-chief::template :title="$title">
    <x-slot name="hero">
        <x-chief::template.hero :title="$title" :breadcrumbs="[$breadcrumb]" class="max-w-3xl">
            <a
                href="{{ route('chief.back.menuitem.create', $menu->key()) }}"
                title="Menu item toevoegen"
                class="btn btn-primary"
            >
                <x-chief-icon-label type="add">Menu item toevoegen</x-chief-icon-label>
            </a>
        </x-chief::template.hero>
    </x-slot>

    <x-chief::template.grid class="max-w-3xl">
        <div class="card">
            @if($menuItems->isEmpty() )
                <p class="body-dark">Momenteel zijn er nog geen menu items toegevoegd.</p>
            @else
                <div class="-my-3 divide-y divide-grey-100">
                    @foreach($menuItems as $item)
                        <x-chief-hierarchy
                            :item="$item"
                            view-path="chief::admin.menu._partials.menu-item"
                            iconMarginTop="0.2rem"
                        />
                    @endforeach
                </div>
            @endif
        </div>
    </x-chief::template.grid>
</x-chief::template>
