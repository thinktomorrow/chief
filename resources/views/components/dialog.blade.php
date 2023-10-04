@props([
    'wired',
    'size' => 'md',
    'title' => null,
    'header' => null,
    'footer' => null,
])

<div {{ $attributes->class(['fixed inset-0 z-[100] flex items-center justify-center']) }}
     x-cloak
     wire:ignore.self
     x-show="open"
     x-data="{
        open: {{ isset($wired) ? '$wire.entangle(\'isOpen\')' : 'false' }},
        close() {
            {{ isset($wired) ? '$wire.close()' : '$data.open = false;' }}
        },
        toggleInnerShadows() {
            if($refs.container.scrollTop !== 0) {
                $refs.headerShadow.classList.remove('opacity-0')
            } else {
                $refs.headerShadow.classList.add('opacity-0')
            }
            if($refs.container.scrollTop !== $refs.container.scrollHeight - $refs.container.clientHeight) {
                $refs.footerShadow.classList.remove('opacity-0')
            } else {
                $refs.footerShadow.classList.add('opacity-0')
            }
        }
    }"
     x-on:open-dialog.window="if($el.id === $event.detail.id || $el === $event.detail.el) { open = true; }"
     x-on:resize.debounce.250ms.window="toggleInnerShadows()"
     x-init="$watch('open', value => { if(value) $nextTick(() => toggleInnerShadows()) })"
>
    <div
            x-on:click="close()"
            class="absolute inset-0 cursor-pointer bg-black/20 backdrop-filter backdrop-blur-sm animate-dialog-fade-in"
    ></div>

    <div class="container relative inline-flex justify-center pointer-events-none max-w-screen-2xl">
        <div @class([
            'bg-white ring-1 ring-inset ring-grey-100 rounded-xl shadow pointer-events-auto animate-dialog-pop-in overflow-hidden',
            'w-xs' => $size === 'xs',
            'w-sm' => $size === 'sm',
            'w-md' => $size === 'md',
            'w-lg' => $size === 'lg',
            'w-xl' => $size === 'xl',
            'w-2xl' => $size === '2xl',
        ])>
            <div
                    x-ref="container"
                    x-on:scroll="toggleInnerShadows()"
                    class="relative overflow-auto max-h-[calc(100vh-4rem)] transition-all duration-150 ease-in-out shadow-black/5"
            >
                <header class="sticky top-0 z-[1]">
                    @if($title || $header)
                        <div class="px-8 pt-8 pb-6 space-y-4 bg-white/[0.85] backdrop-filter backdrop-blur-md">
                            @if($title)
                                <div class="flex items-start justify-between gap-4">
                                    <h2 class="text-lg font-medium leading-5 text-black">
                                        {{ $title }}
                                    </h2>

                                    <button type="button" x-on:click="close()" class="ml-auto shrink-0">
                                        <svg class="w-5 h-5 text-grey-400 hover:body-dark">
                                            <use xlink:href="#icon-x-mark"></use>
                                        </svg>
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

                    <div x-ref="headerShadow"
                         class="absolute left-0 right-0 h-8 overflow-hidden transition-opacity duration-150 ease-in-out opacity-0 pointer-events-none -bottom-8">
                        <div class="h-8 -translate-y-8 bg-black/20 blur"></div>
                    </div>
                </header>

                <div @class(['relative px-8', 'pt-6' => !$header && !$title, 'pb-6' => !$footer])>
                    {{ $slot }}
                </div>

                <footer class="sticky bottom-0 z-[1]">
                    <div x-ref="footerShadow"
                         class="absolute left-0 right-0 h-8 overflow-hidden transition-opacity duration-150 ease-in-out opacity-0 pointer-events-none -top-8">
                        <div class="h-8 translate-y-8 bg-black/20 blur"></div>
                    </div>

                    @if($footer)
                        <div {{ $footer->attributes->class(['px-8 pb-8 pt-6 bg-white/[0.85] backdrop-filter backdrop-blur-md flex flex-wrap justify-end gap-3']) }}>
                            {{ $footer }}
                        </div>
                    @endif
                </footer>
            </div>
        </div>
    </div>
</div>
