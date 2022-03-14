@php
    $repeatedComponents = $getRepeatedComponents($locale ?? null);
@endphp

<div
    data-repeat
    data-repeat-endpoint="{{ $getEndpoint() }}"
    data-repeat-section-name="{{ $getName() }}"
    id="{{ $getId($locale ?? null) }}"
>
    @foreach($repeatedComponents as $components)
        @include($getSectionView())
    @endforeach

    <button type="button" data-add-repeat-section class="btn btn-grey w-full">
        <span class="inline-block w-full text-center">Voeg een extra blok toe</span>
    </button>
</div>

