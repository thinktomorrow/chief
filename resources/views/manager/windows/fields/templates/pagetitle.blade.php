@php
    $type = $componentKey;
    $editRequestUrl = $manager->route('fields-edit', $model, $componentKey);
@endphp

<div class="flex items-start space-x-4">
    <h1>{!! $fields->first()->getValue() !!}</h1>

    <a data-sidebar-trigger="{{ $type ?: '' }}" href="{{ $editRequestUrl }}" class="mt-3 link link-primary">
        <x-chief-icon-label type="edit"></x-chief-icon-label>
    </a>
</div>
