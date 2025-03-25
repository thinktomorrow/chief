@php
    $title = 'Nieuwe ' . $resource->getLabel() . ' aanmaken';
@endphp

<x-chief::page.template :title="$title" container="md">
    <x-slot name="header">
        <x-chief::page.header
            :breadcrumbs="[
                ['label' => 'Dashboard', 'url' => route('chief.back.dashboard'), 'icon' => 'home'],
                ['label' => $resource->getIndexTitle($model), 'url' => $manager->route('index', $model), 'icon' => $resource->getNavItem()?->icon()],
                $title
            ]"
        />
    </x-slot>

    <x-chief::window>
        <form id="createForm" method="POST" action="@adminRoute('store')" enctype="multipart/form-data" role="form">
            @csrf

            <x-chief-form::fields not-tagged="edit,not-on-create" />

            <x-chief::button data-slot="form-group" type="submit" variant="blue">Aanmaken</x-chief::button>
        </form>
    </x-chief::window>

    @push('custom-scripts')
        @include('chief::templates.page._partials.editor-script')
    @endpush
</x-chief::page.template>
