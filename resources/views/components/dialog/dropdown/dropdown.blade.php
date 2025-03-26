@props([
    'wired',
    'placement' => 'bottom-end',
])

<x-chief::dialog :wired="isset($wired)" class="absolute">
    <div
        wire:ignore.self
        x-data="dropdown({ placement: '{{ $placement }}' })"
        x-on:click.outside.stop="close()"
        {{ $attributes->class(['absolute right-0 top-0 z-50 w-max animate-dialog-pop-in rounded-md bg-white/95 shadow-md ring-1 ring-grey-100 backdrop-blur-md']) }}
    >
        <div class="grid grid-cols-[auto_1fr] py-1 [&>*]:w-full [&>*]:text-left">
            {{ $slot }}
        </div>
    </div>
</x-chief::dialog>
