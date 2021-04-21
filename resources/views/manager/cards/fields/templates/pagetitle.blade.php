@php
    $type = 'fields-' . $componentKey;
    $editRequestUrl = $manager->route('fields-edit', $model, $componentKey);
@endphp

<div class="flex items-start space-x-4">
    <h1>{!! $fields->first()->getValue() !!}</h1>

    <a data-sidebar-{{ $type ?: '' }}-edit href="{{ $editRequestUrl }}" class="link link-primary">
        <x-icon-label type="edit"></x-icon-label>
    </a>
</div>
