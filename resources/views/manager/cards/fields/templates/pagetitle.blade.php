<?php

    $type = 'fields-' . $componentKey;
    $editRequestUrl = $manager->route('fields-edit', $model, $componentKey);

?>

<h1 class="mr-8 center-y">
    <span>{!! $fields->first()->getValue() !!}</span>
    <a data-sidebar-{{ $type ?: '' }}-edit href="{{ $editRequestUrl }}" class="link link-black ml-4">
        <x-link-label type="edit"></x-link-label>
    </a>
</h1>
