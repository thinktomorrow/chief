@props([
    'wired',
])

<x-chief::dialog>
    <div {{ $attributes->class(['fixed inset-0 z-50']) }}>
        <div
            class="absolute inset-0 animate-dialog-fade-in cursor-pointer bg-black/20 backdrop-blur-sm backdrop-filter"
        ></div>

        <div x-on:click.outside="close()" class="absolute bottom-0 right-0 top-0 max-w-lg bg-white">
            {{ $slot }}
        </div>
    </div>
</x-chief::dialog>
