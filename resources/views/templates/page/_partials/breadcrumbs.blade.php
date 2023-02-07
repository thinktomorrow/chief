@php
    // Using array_filter to removed null elements in array
    $breadcrumbs = array_filter($breadcrumbs ?? []);

    // If no breadcrumbs are given and the current route name isn't dashboard, generate a dashboard breadcrumb
    if(count($breadcrumbs) == 0 && Illuminate\Support\Facades\Route::currentRouteName() != 'chief.back.dashboard') {
        $breadcrumbs = [new Thinktomorrow\Chief\Admin\Nav\BreadCrumb('Dashboard', route('chief.back.dashboard'))];
    }
@endphp

@foreach ($breadcrumbs as $breadcrumb)
    <a href="{{ visitedUrl($breadcrumb->url) }}" title="{{ $breadcrumb->label }}" class="link link-primary">
        <x-chief-icon-label type="back">{{ $breadcrumb->label }}</x-chief-icon-label>
    </a>
@endforeach
