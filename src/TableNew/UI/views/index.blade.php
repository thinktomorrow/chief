@php
    $listingComponent = \Thinktomorrow\Chief\TableNew\UI\Livewire\ArticleListing::class;
@endphp

<x-chief::page.template>
    <div class="container my">
        <livewire:is :component="$listingComponent" />
    </div>
</x-chief::page.template>
