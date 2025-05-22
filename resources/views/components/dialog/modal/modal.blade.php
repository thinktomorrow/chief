@props([
    'wired',
    'size' => 'md',
    'title' => null,
    'subtitle' => null,
    'header' => null,
    'footer' => null,
])

<x-chief::dialog :wired="isset($wired)">
    <div
        {{ $attributes->class(['fixed inset-0 z-[100] flex items-center justify-center']) }}
    >
        <div
            x-on:click.stop="close()"
            class="animate-dialog-fade-in absolute inset-0 cursor-pointer bg-black/15 backdrop-blur-[2px]"
        ></div>

        <div class="pointer-events-none relative container inline-flex max-w-screen-2xl justify-center">
            <div
                @class([
                    'animate-dialog-pop-in ring-grey-100 pointer-events-auto rounded-xl bg-white/[0.98] shadow-md ring-1 backdrop-blur-md',
                    'w-96' => $size === 'xxs',
                    'w-[480px]' => $size === 'xs',
                    'w-[640px]' => $size === 'sm',
                    'w-[768px]' => $size === 'md',
                    'w-[1024px]' => $size === 'lg',
                    'w-[1280px]' => $size === 'xl',
                    'w-[1536px]' => $size === '2xl',
                ])
            >
                <div
                    wire:ignore.self
                    @class([
                        'flex max-h-[calc(100vh-4rem)] flex-col transition-all duration-150 ease-in-out',
                        '[&>[data-slot=header]+[data-slot=content]]:pt-px',
                    ])
                >
                    @if ($header)
                        <div data-slot="header" {{ $header->attributes }}>
                            {{ $header }}
                        </div>
                    @else
                        <x-chief::dialog.modal.header data-slot="header" :title="$title" :subtitle="$subtitle" />
                    @endif

                    <div data-slot="content" class="overflow-y-auto px-4 py-6">
                        {{ $slot }}
                    </div>

                    @if ($footer)
                        <div data-slot="footer" {{ $footer->attributes }}>
                            {{ $footer }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-chief::dialog>
