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
            class="absolute inset-0 animate-dialog-fade-in cursor-pointer bg-primary-100/10 backdrop-blur-[2px]"
        ></div>

        <div class="container pointer-events-none relative inline-flex max-w-screen-2xl justify-center">
            <div
                @class([
                    'pointer-events-auto animate-dialog-pop-in rounded-xl bg-white/95 shadow-md ring-1 ring-grey-100 backdrop-blur-sm',
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

                    <div data-slot="content" class="overflow-y-auto p-4">
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
