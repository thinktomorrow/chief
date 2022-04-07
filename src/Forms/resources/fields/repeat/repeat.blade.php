@php
    $repeatedComponents = $getRepeatedComponents($locale ?? null);
@endphp

<div
    data-repeat
    data-repeat-endpoint="{{ $getEndpoint() }}"
    data-repeat-section-name="{{ $getName() }}"
    id="{{ $getId($locale ?? null) }}"
    class="space-y-3"
>
    @foreach($repeatedComponents as $components)
        @include($getSectionView())
    @endforeach

    <button type="button" data-add-repeat-section class="w-full text-center btn btn-grey">
        Voeg een extra blok toe
    </button>
</div>
