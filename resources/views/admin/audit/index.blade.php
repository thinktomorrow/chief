<x-chief::page.template title="Audit">
    <x-slot name="hero">
        <x-chief::page.hero title="Audit" class="max-w-3xl"/>
    </x-slot>

    <x-chief::page.grid class="max-w-3xl">
        @include('chief::admin.audit._rows')
    </x-chief::page.grid>
</x-chief::page.template>
