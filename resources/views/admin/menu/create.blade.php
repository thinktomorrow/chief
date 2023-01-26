@php
    $breadcrumb = new \Thinktomorrow\Chief\Admin\Nav\Breadcrumb('Terug naar het menu', route('chief.back.menus.show', $menuitem->menu_type));
@endphp

<x-chief::template title="Menu item toevoegen">
    <x-slot name="hero">
        <x-chief::template.hero title="Menu item toevoegen" :breadcrumbs="[$breadcrumb]" class="max-w-3xl">
            <button form="createForm" type="submit" class="btn btn-primary">Menu item toevoegen</button>
        </x-chief::template.hero>
    </x-slot>

    <x-chief::template.grid class="max-w-3xl">
        <form
            id="createForm"
            method="POST"
            action="{{ route('chief.back.menuitem.store') }}"
            enctype="multipart/form-data"
            role="form"
            class="card"
        >
            @csrf

            <input type="hidden" name="menu_type" value="{{ $menuitem->menu_type }}">

            @include('chief::admin.menu._partials.form')
        </form>
    </x-chief::template.grid>
</x-chief::template>
