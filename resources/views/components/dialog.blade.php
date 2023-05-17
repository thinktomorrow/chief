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

    <div class="relative p-12 bg-white rounded-lg w-[56rem] animate-dialog-pop-in border border-grey-100 shadow">
        <button type="button" x-on:click="open = false" class="absolute top-6 right-6">
            <svg class="w-6 h-6 text-grey-500 hover:body-dark"><use xlink:href="#icon-x-mark"></use></svg>
        </button>

        <div>
            {{ $slot }}
        </div>
    </div>
</div>
