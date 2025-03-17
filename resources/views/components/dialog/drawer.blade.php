@props([
    'wired',
    'size' => 'sm',
    'title' => null,
    'footer' => null,
])

<x-chief::dialog :wired="isset($wired)">
    <div {{ $attributes->class(['fixed inset-0 z-50']) }}>
        <div class="absolute inset-0 animate-dialog-fade-in cursor-pointer bg-black/10 backdrop-blur-[2px]"></div>

        <div class="pointer-events-none relative flex h-full w-full animate-slide-in-nav justify-end p-4">
            <div
                x-on:click.outside="close()"
                @class([
                    'pointer-events-auto flex w-full flex-col rounded-2xl bg-white shadow',
                    'xs:w-sm' => $size === 'sm',
                    'sm:w-md' => $size === 'md',
                    'md:w-lg' => $size === 'lg',
                ])
            >
                <div class="flex shrink-0 justify-between border-b border-grey-100 p-4">
                    <h2 class="mt-[0.1875rem] text-lg/6 font-medium text-grey-950">
                        {{ $title ?? 'Default drawer title' }}
                    </h2>

                    <x-chief::button variant="transparent" type="button" x-on:click="close()" class="ml-auto shrink-0">
                        <x-chief::icon.cancel />
                    </x-chief::button>
                </div>

                <div class="grow overflow-y-auto p-4">
                    {{ $slot }}
                </div>

                @if ($footer)
                    <div {{ $footer->attributes->class(['shrink-0 border-t border-grey-100 p-4']) }}>
                        {{ $footer }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-chief::dialog>
