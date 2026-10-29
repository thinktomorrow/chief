<x-chief::page.template :title="$guide->title" container="lg">
    <x-slot name="header">
        @include ('chief-docs::breadcrumbs')
    </x-slot>

    <x-chief::window>
        <div class="prose prose-dark prose-spacing max-w-none">{!! $content !!}</div>
    </x-chief::window>
</x-chief::page.template>
