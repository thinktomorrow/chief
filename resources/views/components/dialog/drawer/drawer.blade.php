@props([
    'wired',
    'size' => 'md',
    'title' => null,
    'subtitle' => null,
    'badges' => [],
    'edgeToEdge' => false,
    'header' => null,
    'footer' => null,
])

<x-chief::dialog :wired="isset($wired)">
    <div {{ $attributes->class(['fixed inset-0 z-50']) }}>
        <div
            x-on:click.stop="close()"
            class="absolute inset-0 animate-dialog-fade-in cursor-pointer bg-black/25 backdrop-blur-[2px]"
        ></div>

        <div class="pointer-events-none relative flex h-full w-full animate-slide-in-nav justify-end p-2">
            <div
                @class([
                    'pointer-events-auto flex w-full flex-col rounded-xl bg-white/[0.98] shadow-md ring-1 ring-grey-100 backdrop-blur-md',
                    'xs:w-sm' => $size === 'sm',
                    'sm:w-md' => $size === 'md',
                    'md:w-lg' => $size === 'lg',
                ])
            >
                @if ($header)
                    <div {{ $header->attributes }}>
                        {{ $header }}
                    </div>
                @else
                    <x-chief::dialog.drawer.header :title="$title" :subtitle="$subtitle" :badges="$badges" />
                @endif

                <div
                    @class([
                        'grow overflow-y-auto',
                        'px-4 py-6' => $edgeToEdge === false,
                    ])
                >
                    {{ $slot }}
                </div>

                @if ($footer)
                    <div {{ $footer->attributes }}>
                        {{ $footer }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-chief::dialog>
