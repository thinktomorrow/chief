<x-chief::dialog.drawer
    wired
    size="sm"
    title="Op welke sites wil je deze content zichtbaar maken?"
    :edge-to-edge="false"
>
    @if ($isOpen)
        <x-chief::form.fieldset class="w-full space-y-3">
            @foreach ($this->getAvailableSites() as $site)
                <label
                    for="{{ $site->locale }}"
                    @class([
                        'flex items-start gap-3 rounded-xl border border-grey-200 p-4',
                        '[&:has(input[type=checkbox]:checked)]:border-blue-200 [&:has(input[type=checkbox]:checked)]:bg-blue-50',
                    ])
                >
                    <x-chief::form.input.checkbox
                        wire:model="form.locales"
                        id="{{ $site->locale }}"
                        value="{{ $site->locale }}"
                        class="shrink-0"
                    />

                    <div class="flex grow items-start justify-between gap-2">
                        <div class="space-y-2">
                            <p class="font-medium leading-5 text-grey-700">{{ $site->name }}</p>

                            <p class="leading-5 text-grey-500">{{ $site->url }}</p>
                        </div>
                    </div>
                </label>
            @endforeach
        </x-chief::form.fieldset>

        <x-slot name="footer">
            <x-chief::dialog.drawer.footer>
                <x-chief::button wire:click="save" variant="blue">Bewaren</x-chief::button>
                <x-chief::button wire:click="close">Annuleer</x-chief::button>
            </x-chief::dialog.drawer.footer>
        </x-slot>

    @endif
</x-chief::dialog.drawer>
