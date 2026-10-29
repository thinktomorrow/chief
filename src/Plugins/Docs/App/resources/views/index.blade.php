<x-chief::page.template title="Documentatie" container="lg">
    <x-chief::window>
        @include ('chief-docs::tree', ['nodes' => $tree->roots()])
    </x-chief::window>
</x-chief::page.template>
