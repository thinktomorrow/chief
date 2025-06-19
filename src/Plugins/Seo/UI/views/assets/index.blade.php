<x-chief::page.template title="Alt teksten" container="xl">
    <x-slot name="header">
        <x-chief::page.header
            :breadcrumbs="[
                ['label' => 'Seo', 'url' => route('chief.seo.index')],
                'Alt teksten'
            ]"
        />
    </x-slot>

    <livewire:chief-wire::table key="seo-assets-table" :table="$table" />

    <livewire:chief-wire::file-edit parent-id="seo-asset" />
    {{--    <livewire:chief-wire::edit-asset />--}}

</x-chief::page.template>
