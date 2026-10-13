<x-chief::page.template title="Documentatie" container="lg">
    <x-slot name="header">
        @include ('chief-docs::breadcrumbs', ['page' => $page])
    </x-slot>

    <x-chief::window>
        <div class="prose prose-dark prose-spacing max-w-none">{!! $html !!}</div>
    </x-chief::window>
</x-chief::page.template>
