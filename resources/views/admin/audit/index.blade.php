<x-chief::template title="Audit">
    <x-slot name="hero">
        <x-chief::template.hero title="Audit" class="max-w-3xl"/>
    </x-slot>

    <x-chief::template.grid class="max-w-3xl">
        @include('chief::admin.audit._rows')
    </x-chief::template.grid>
</x-chief::template>
