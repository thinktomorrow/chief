@php
    // Using array_filter to removed null elements in array
    $breadcrumbs = array_filter($breadcrumbs ?? []);
@endphp

@foreach ($breadcrumbs as $breadcrumb)
    <a href="{{ visitedUrl($breadcrumb->url) }}" title="{{ $breadcrumb->label }}" class=" text-sm link link-primary">
        <x-chief::icon-label type="back">{{ $breadcrumb->label }}</x-chief::icon-label>
    </a>
@endforeach
