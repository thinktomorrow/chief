<x-chief::dialog.drawer wired size="sm" title="Bewerk links">
    @if ($isOpen)

        <div class="space-y-4">
            @foreach ($this->getSites() as $i => $site)
                <x-chief::callout :title="$site->name" wire:key="{{ $site->locale }}" variant="outline-white">
                    <div class="space-y-3">
                        <div class="flex items-start justify-between gap-2">
                            <div class="mt-[0.1875rem] flex items-center gap-2">
                                <p class="text-sm/6 font-medium text-grey-500">{{ $site->url }}</p>
                            </div>
                        </div>

                        <x-chief::form.fieldset rule="form.{{ $site->locale }}.context">
                            <x-chief::form.label for="context">Inhoud</x-chief::form.label>
                            <x-chief::form.input.select id="context"
                                                        wire:model.change="form.{{ $site->locale }}.context">
                                @foreach ($contexts as $context)
                                    <option wire:key="context-option-{{ $context->id }}" value="{{ $context->id }}">
                                        {{ $context->title }}
                                    </option>
                                @endforeach
                            </x-chief::form.input.select>
                        </x-chief::form.fieldset>
                    </div>
                </x-chief::callout>
            @endforeach
        </div>


        <x-slot name="footer">
            <x-chief::dialog.drawer.footer>
                <x-chief::button wire:click="save" variant="blue">Bewaren</x-chief::button>
                <x-chief::button wire:click="close">Annuleer</x-chief::button>
            </x-chief::dialog.drawer.footer>
        </x-slot>

    @endif
</x-chief::dialog.drawer>
