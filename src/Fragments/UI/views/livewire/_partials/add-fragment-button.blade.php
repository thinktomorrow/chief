@php
    $order ??= -1;
    $parentId ??= null;
@endphp

<div data-slot="add-fragment-button" class="pointer-events-none relative">
    <div class="absolute z-[1] flex h-8 w-full justify-center">
        <x-chief::button
            x-on:click="$wire.addFragment({{ $order }}, '{{ $parentId }}')"
            size="sm"
            class="pointer-events-auto absolute -top-3.5"
        >
            <x-chief::icon.plus-sign />
        </x-chief::button>
    </div>
</div>
