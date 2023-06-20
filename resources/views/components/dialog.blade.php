{{-- How to use:
- Livewire modals
    <button type="button" wire:click="toggleDialog()">Open wired dialog</button>
    <x-chief::dialog wired>
        @if($isOpen)
            ...
        @endif
    </x-chief::dialog>

- Normal modals
    <button type="button" x-data x-on:click="$dispatch('open-dialog', { 'id': 'the-normal-modal' })">
        Open normal dialog
    </button>
    <x-chief::dialog id="the-normal-modal">
        ...
    </x-chief::dialog>
--}}

@props([
    'wired'
])

<div
    x-cloak
    x-show="open"
    @isset($wired) x-data="{ open: $wire.entangle('isOpen') }" @else x-data="{ open: false }" @endisset
    x-on:open-dialog.window="if($el.id === $event.detail.id) { open = true; }"
    class="fixed inset-0 z-[100] flex items-center justify-center"
    {{ $attributes }}
>
    <div x-on:click="open = false" class="absolute inset-0 cursor-pointer bg-black/20 animate-dialog-fade-in"></div>

    <div class="container relative inline-flex justify-center pointer-events-none max-w-screen-2xl">
        <div class="relative p-8 bg-white border rounded-lg shadow pointer-events-auto sm:p-12 animate-dialog-pop-in border-grey-100">
            <button type="button" x-on:click="open = false" class="absolute top-3 right-3 sm:top-4 sm:right-4">
                <svg class="w-5 h-5 text-grey-500 hover:body-dark"><use xlink:href="#icon-x-mark"></use></svg>
            </button>

            <div>
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
