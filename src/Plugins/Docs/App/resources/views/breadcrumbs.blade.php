@php
    $title = config('chief-docs.pages.'.$page.'.title') ?: Str::headline($page);
    $parent = config('chief-docs.pages.'.$page.'.parent');
    $breadcrumbs = [['label' => 'Documentatie', 'url' => route('chief.docs.index')]];

    if ($parent && $parent !== $page && is_file(resource_path('docs/'.$parent.'.md'))) {
        $breadcrumbs[] = [
            'label' => config('chief-docs.pages.'.$parent.'.title') ?: Str::headline($parent),
            'url' => route('chief.docs.show', $parent),
        ];
    }

    $breadcrumbs[] = $title;
@endphp

<x-chief::page.header title="Documentatie" :breadcrumbs="$breadcrumbs" />
