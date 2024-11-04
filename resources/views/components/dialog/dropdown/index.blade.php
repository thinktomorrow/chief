@props([
    'wired',
    'placement' => 'bottom-end',
])

<x-chief::dialog class="absolute">
    <div
        x-data="dropdown({ placement: '{{ $placement }}' })"
        x-on:click.outside="close()"
        {{ $attributes->class(['absolute right-0 top-0 z-50 w-max animate-dialog-pop-in rounded-md bg-white shadow-lg ring-1 ring-black/5']) }}
    >
        <div class="grid grid-cols-[auto_1fr] py-1 [&>*]:w-full [&>*]:text-left">
            {{ $slot }}
        </div>
    </div>
</x-chief::dialog>
