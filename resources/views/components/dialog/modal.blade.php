@props([
    'wired',
    'size' => 'md',
    'title' => null,
    'subtitle' => null,
    'header' => null,
    'footer' => null,
    'id' => null,
])

<x-chief::dialog :wired="isset($wired)">
    <div
        x-data="{
            toggleInnerShadows() {
                if ($refs.container.scrollTop !== 0) {
                    $refs.headerShadow.classList.remove('opacity-0')
                } else {
                    $refs.headerShadow.classList.add('opacity-0')
                }
                if (
                    $refs.container.scrollTop !==
                    $refs.container.scrollHeight - $refs.container.clientHeight
                ) {
                    $refs.footerShadow.classList.remove('opacity-0')
                } else {
                    $refs.footerShadow.classList.add('opacity-0')
                }
            },
        }"
        x-on:resize.debounce.250ms.window="toggleInnerShadows()"
        x-init="
{{--            $watch('isOpen', (value) => {--}}
{{--                if (value) $nextTick(() => toggleInnerShadows())--}}
{{--            })--}}
        "
        {{ $attributes->class(['fixed inset-0 z-[100] flex items-center justify-center']) }}
    >
        <div
            x-on:click="close()"
            class="absolute inset-0 animate-dialog-fade-in cursor-pointer bg-black/20 backdrop-blur-sm backdrop-filter"
        ></div>

        <div class="container pointer-events-none relative inline-flex max-w-screen-2xl justify-center">
            <div
                @class([
                    'pointer-events-auto animate-dialog-pop-in overflow-hidden rounded-2xl rounded-xl bg-white shadow-lg ring-1 ring-black/5',
                    'w-96' => $size === 'xxs',
                    'w-xs' => $size === 'xs',
                    'w-sm' => $size === 'sm',
                    'w-md' => $size === 'md',
                    'w-lg' => $size === 'lg',
                    'w-xl' => $size === 'xl',
                    'w-2xl' => $size === '2xl',
                ])
            >
                <div
                    x-ref="container"
                    x-on:scroll="toggleInnerShadows()"
                    class="relative max-h-[calc(100vh-4rem)] overflow-auto shadow-black/5 transition-all duration-150 ease-in-out"
                >
                    <header class="sticky top-0 z-[1]">
                        @if ($title || $header || $subtitle)
                            <div class="space-y-4 bg-white/[0.85] px-6 pb-4 pt-6 backdrop-blur-md backdrop-filter">
                                @if ($title || $subtitle)
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="space-y-1">
                                            @if ($title)
                                                <h2 class="text-lg font-medium leading-5 text-black">
                                                    {{ $title }}
                                                </h2>
                                            @endif

                                            @if ($subtitle)
                                                <p class="body text-sm leading-5 text-grey-500">
                                                    {{ $subtitle }}
                                                </p>
                                            @endif
                                        </div>

                                        <button type="button" x-on:click="close()" class="ml-auto shrink-0">
                                            <svg class="hover:body-dark h-5 w-5 text-grey-400">
                                                <use xlink:href="#icon-x-mark"></use>
                                            </svg>
                                        </button>
                                    </div>
                                @endif

                                @if ($header)
                                    <div {{ $header->attributes }}>
                                        {{ $header }}
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div
                            x-ref="headerShadow"
                            class="pointer-events-none absolute -bottom-8 left-0 right-0 h-8 overflow-hidden opacity-0 transition-opacity duration-150 ease-in-out"
                        >
                            <div class="h-8 -translate-y-8 bg-black/20 blur"></div>
                        </div>
                    </header>

                    <div @class(['relative px-6', 'pt-4' => ! $header && ! $title, 'pb-6' => ! $footer])>
                        {{ $slot }}
                    </div>

                    <footer class="sticky bottom-0 z-[1]">
                        <div
                            x-ref="footerShadow"
                            class="pointer-events-none absolute -top-8 left-0 right-0 h-8 overflow-hidden opacity-0 transition-opacity duration-150 ease-in-out"
                        >
                            <div class="h-8 translate-y-8 bg-black/20 blur"></div>
                        </div>

                        @if ($footer)
                            <div
                                {{ $footer->attributes->class(['flex flex-wrap justify-end gap-2 bg-white/[0.85] px-6 pb-8 pt-6 backdrop-blur-md backdrop-filter']) }}
                            >
                                {{ $footer }}
                            </div>
                        @endif
                    </footer>
                </div>
            </div>
        </div>
    </div>
</x-chief::dialog>
