@php
    $breadcrumb = isset($resource) ? $resource->getPageBreadCrumb() : null;
@endphp

@if($breadcrumb || (isset($manager) && $manager->can('index')))
    <div class="container mb-2">
        @if($breadcrumb)
            <a href="{{ visitedUrl($breadcrumb->url) }}" title="{{ $breadcrumb->label }}" class="link link-primary">
                <x-chief-icon-label type="back">{{ $breadcrumb->label }}</x-chief-icon-label>
            </a>
        @else
            @adminCan('index')
                <a href="{{ visitedUrl($manager->route('index')) }}" title="Overzicht" class="link link-primary">
                    <x-chief-icon-label type="back">Overzicht</x-chief-icon-label>
                </a>
            @endAdminCan
        @endif
    </div>
@endif
