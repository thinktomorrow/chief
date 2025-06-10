<x-chief::page.template title="Alt teksten" container="xl">
    <x-slot name="header">
        <x-chief::page.header
            :breadcrumbs="[
                ['label' => 'Seo', 'url' => route('chief.seo.index')],
                'Alt teksten'
            ]"
        />
    </x-slot>

    <livewire:chief-wire::table key="seo-alt-table" :table="$table" />

    <livewire:chief-wire::edit-alt />

</x-chief::page.template>
