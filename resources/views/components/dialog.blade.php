@props([
    'wired',
    'size' => 'md',
    'title' => null,
    'header' => null,
    'footer' => null,
])

<div
    @isset($wired) x-data="{ open: $wire.entangle('isOpen') }" @else x-data="{ open: false }" @endisset
    x-cloak
    x-show="open"
    x-on:open-dialog.window="if($el.id === $event.detail.id) { open = true; }"
    class="fixed inset-0 z-[100] flex items-center justify-center"
    {{ $attributes }}
>
    <div x-on:click="open = false" class="absolute inset-0 cursor-pointer bg-black/20 backdrop-filter backdrop-blur-sm animate-dialog-fade-in"></div>

    <div class="container relative inline-flex justify-center pointer-events-none max-w-screen-2xl">
        <div
        @class([
            'bg-white ring-1 ring-inset ring-grey-100 rounded-xl shadow pointer-events-auto animate-dialog-pop-in overflow-hidden',
            'w-xs' => $size === 'xs',
            'w-sm' => $size === 'sm',
            'w-md' => $size === 'md',
            'w-lg' => $size === 'lg',
            'w-xl' => $size === 'xl',
            'w-2xl' => $size === '2xl',
        ])>
            <div class="relative overflow-auto max-h-[calc(100vh-4rem)]">
                @if($title || $header)
                    <div class="sticky top-0 z-[1] px-8 pt-8 pb-6 bg-white/90 backdrop-filter backdrop-blur-md space-y-4">
                        @if($title)
                            <div class="flex items-start justify-between gap-4">
                                <h2 class="text-lg font-medium leading-5 text-black">
                                    {{ $title }}
                                </h2>

                                <button type="button" x-on:click="open = false" class="ml-auto shrink-0">
                                    <svg class="w-5 h-5 text-grey-400 hover:body-dark"><use xlink:href="#icon-x-mark"></use></svg>
                                </button>
                            </div>
                        @endif

                        @if($header)
                            <div {{ $header->attributes }}>
                                {{ $header }}
                            </div>
                        @endif
                    </div>
                @endif


                <div @class(['relative px-8', 'pt-6' => !$header && !$title, 'pb-6' => !$footer])>
                    {{ $slot }}
                </div>

                @if($footer)
                    <div {{ $footer->attributes->class(['sticky bottom-0 px-8 pb-8 pt-6 bg-white/90 backdrop-filter backdrop-blur-md z-[1]']) }}>
                        {{ $footer }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
