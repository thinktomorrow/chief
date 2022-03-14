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

    <button type="button" data-add-repeat-section class="btn mt-4 w-full text-center">+ voeg nieuwe blok toe</button>
</div>

