@php
    $breadcrumb = new \Thinktomorrow\Chief\Admin\Nav\Breadcrumb('Terug naar het menu', route('chief.back.menus.show', $menuitem->menu_type));
@endphp

<x-chief::page.template title="Menu item bewerken">
    <x-slot name="hero">
        <x-chief::page.hero title="Menu item bewerken" :breadcrumbs="[$breadcrumb]" class="max-w-3xl">
            <div class="space-x-3">
                <span class="btn btn-error-outline" @click="showModal('delete-menuitem-{{$menuitem->id}}')">
                    Verwijderen
                </span>

                <button form="updateForm" type="submit" class="btn btn-primary">Opslaan</button>
            </div>
        </x-chief::page.hero>
    </x-slot>

    <x-chief::page.grid class="max-w-3xl">
        <form
            id="updateForm"
            method="POST"
            action="{{ route('chief.back.menuitem.update', $menuitem->id) }}"
            enctype="multipart/form-data"
            role="form"
            class="card"
        >
            @csrf
            @method('put')

            @include('chief::admin.menu._partials.form')
        </form>
    </x-chief::page.grid>

    @include('chief::admin.menu._partials.delete-modal')
</x-chief::page.template>
