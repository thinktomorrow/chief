@props([
    'wired',
    'placement' => 'bottom-end',
    'offset' => 8,
])

<x-chief::dialog :wired="isset($wired)" class="absolute">
    <div
        wire:ignore.self
        x-data="dropdown({ placement: '{{ $placement }}', offset: {{ $offset }} })"
        x-on:click.outside.stop="close()"
        {{ $attributes->class(['animate-dialog-pop-in ring-grey-100 absolute top-0 right-0 z-50 w-max rounded-md bg-white/95 shadow-md ring-1 backdrop-blur-md']) }}
    >
        <div class="grid grid-cols-[auto_1fr] py-1 *:w-full *:text-left">
            {{ $slot }}
        </div>
    </div>
</x-chief::dialog>
