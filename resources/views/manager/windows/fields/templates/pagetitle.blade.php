@php
    $type = $componentKey;
    $editRequestUrl = $manager->route('fields-edit', $model, $componentKey);
@endphp

<div class="flex items-start space-x-4">
    <h1>{!! $fields->first()->first()->getValue() !!}</h1>

    <a data-sidebar-trigger="{{ $type ?: '' }}" href="{{ $editRequestUrl }}" class="link link-primary mt-3">
        <x-icon-label type="edit"></x-icon-label>
    </a>
</div>
