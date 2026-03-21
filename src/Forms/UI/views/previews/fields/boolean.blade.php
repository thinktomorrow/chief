@php
    $selected = (array) $getValueOrFallback($locale ?? null);
    $selected = $selected[0] ?? null;
@endphp

@if ($selected)
    <div class="bg-grey-100 inline-block rounded-full">
        <x-chief::icon.checkmark-circle class="text-grey-500 -m-0.5 size-6" />
    </div>
@else
    <div class="bg-grey-100 inline-block rounded-full">
        <x-chief::icon.cancel-circle class="text-grey-500 -m-0.5 size-6" />
    </div>
@endif
