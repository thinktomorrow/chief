@props([
    'wired',
    'title' => null,
    'footer' => null,
])

<x-chief::dialog>
    <div {{ $attributes->class(['fixed inset-0 z-50']) }}>
        <div
            class="absolute inset-0 animate-dialog-fade-in cursor-pointer bg-black/20 backdrop-blur-sm backdrop-filter"
        ></div>

        <div x-on:click.outside="close()" class="relative flex h-full w-full justify-end">
            <div class="flex w-full flex-col bg-white shadow xs:w-xs">
                <div class="flex shrink-0 justify-between border-b border-grey-200 p-6">
                    <h2 class="text-lg font-medium leading-5 text-black">
                        {{ $title ?? 'Default drawer title' }}
                    </h2>

                    <button type="button" x-on:click="close()" class="ml-auto shrink-0">
                        <svg class="hover:body-dark h-5 w-5 text-grey-400">
                            <use xlink:href="#icon-x-mark"></use>
                        </svg>
                    </button>
                </div>

                <div class="grow overflow-y-auto p-6">
                    {{ $slot }}
                </div>

                @if ($footer)
                    <div {{ $footer->attributes->class(['shrink-0 border-t border-grey-200 px-6 py-4']) }}>
                        {{ $footer }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-chief::dialog>
