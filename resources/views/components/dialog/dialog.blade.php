@props(['wired' => false])

<div
    wire:ignore.self
    x-cloak
    x-data="dialog({
                isOpen: {{ $wired ? '$wire.entangle(\'isOpen\')' : 'false' }},
                wired: {{ $wired ? 'true' : 'false' }},
            })"
    x-show="$data.isOpen"
    {{ $attributes->class(['absolute']) }}
>
    {{ $slot }}
</div>
