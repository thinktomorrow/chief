@php
    $order ??= -1;
    $parentId ??= null;
@endphp

<div class="relative w-full">
    <div class="pointer-events-none absolute z-[1] flex h-8 w-full justify-center">
        <x-chief-table::button
            x-on:click="$wire.addFragment({{ $order }}, '{{ $parentId }}')"
            size="sm"
            class="pointer-events-auto absolute -top-3.5"
        >
            <x-chief::icon.plus-sign />
        </x-chief-table::button>
    </div>
</div>
