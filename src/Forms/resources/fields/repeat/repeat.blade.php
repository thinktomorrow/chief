@php
    $repeatedComponents = $getRepeatedComponents($locale ?? null);
@endphp

<div
    data-slot="control"
    data-repeat
    data-repeat-endpoint="{{ $getEndpoint() }}"
    data-repeat-section-name="{{ $getName() }}"
    id="{{ $getElementId($locale ?? null) }}"
    class="space-y-3"
>
    @foreach ($repeatedComponents as $components)
        @include($getSectionView())
    @endforeach

    <button type="button" data-add-repeat-section class="btn btn-grey w-full">
        <span class="inline-block w-full text-center">Voeg een extra blok toe</span>
    </button>
</div>
