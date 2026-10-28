@php
    $isActive = ! $isOverview && $guide && $guide['id'] === $node['id'];
    $inBranch = $isActive || collect($breadcrumbs)->pluck('id')->contains($node['id']);
    $sections = $isActive ? array_filter($toc, fn ($item) => $item['level'] === 2) : [];
@endphp
<x-chief::nav.item :label="e($label ?? $node['title'])" :url="$node['url']" :is-active="$isActive" :open="$inBranch">
    @foreach ($sections as $item)
        <x-chief::nav.item :label="e($item['label'])" :url="'#'.$item['slug']" />
    @endforeach
    @foreach ($node['children'] ?? [] as $child)
        @include('chief-docs::prototype.d-nav-item', ['node' => $child, 'label' => null])
    @endforeach
</x-chief::nav.item>
