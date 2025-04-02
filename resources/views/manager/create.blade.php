@php
    $title = 'Nieuwe ' . $resource->getLabel() . ' aanmaken';
@endphp

<x-chief::page.template :title="$title" container="md">
    <x-slot name="header">
        <x-chief::page.header
            :breadcrumbs="[
                ['label' => $resource->getIndexTitle(), 'url' => $manager->route('index'), 'icon' => $resource->getNavItem()?->icon()],
                $title
            ]"
        />
    </x-slot>

    <x-chief::window>
        <form id="createForm" method="POST" action="@adminRoute('store')" enctype="multipart/form-data" role="form">
            @csrf

            @foreach ($layout->filterByNotTagged(['edit', 'not-on-model-create'])->getComponents() as $component)
                {{ $component->displayAsInlineForm()->render() }}
            @endforeach

            <x-chief::button data-slot="form-group" type="submit" variant="blue">Aanmaken</x-chief::button>
        </form>
    </x-chief::window>

    @include('chief::templates.page._partials.editor-script')
</x-chief::page.template>
