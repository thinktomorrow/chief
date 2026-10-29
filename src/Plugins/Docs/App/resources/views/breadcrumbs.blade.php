@php
    $breadcrumbs = [['label' => 'Documentatie', 'url' => route('chief.docs.index')]];

    foreach ($tree->ancestors($guide) as $ancestor) {
        $breadcrumbs[] = $ancestor->hasPage()
            ? ['label' => $ancestor->title, 'url' => route('chief.docs.show', $ancestor->id)]
            : $ancestor->title;
    }

    $breadcrumbs[] = $guide->title;
@endphp

<x-chief::page.header :title="$guide->title" :breadcrumbs="$breadcrumbs" />
