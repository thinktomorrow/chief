<div class="space-y-4">
    @foreach ($this->sites as $i => $site)
        <x-chief::callout :title="$site->site->name" wire:key="{{ $site->locale }}" variant="outline-white">
            <div class="space-y-3">
                <div class="flex items-start justify-between gap-2">
                    <div class="mt-[0.1875rem] flex items-start gap-2">
                        @if ($site->url)
                            <p class="my-0.5 text-sm/5 font-medium text-grey-500">{{ $site->url->url }}</p>
                        @endif

                        <x-chief::badge variant="green" size="sm">
                            {{ $site->status->value }}
                        </x-chief::badge>
                    </div>

                    @if ($this->queuedForDeletion($site->locale))
                        <x-chief::button
                            x-on:click="$wire.undoDeleteSite('{{ $site->locale }}')"
                            variant="grey"
                            size="sm"
                        >
                            <x-chief::icon.arrow-turn-backward />
                            <span>Ongedaan maken</span>
                        </x-chief::button>
                    @else
                        <x-chief::button
                            x-on:click="$wire.deleteSite('{{ $site->locale }}')"
                            variant="outline-red"
                            size="sm"
                        >
                            <x-chief::icon.delete />
                        </x-chief::button>
                    @endif
                </div>

                @if (! $this->queuedForDeletion($site->locale))
                    {{-- @dd($site) --}}
                    <div>
                        <div class="row-start-start gutter-2">
                            <div class="w-full">
                                <x-chief::form.fieldset rule="slug">
                                    <x-chief::form.label for="slug" required>Slug</x-chief::form.label>
                                    <x-chief::form.input.prepend-append>
                                        <x-slot name="prepend">
                                            <x-chief::icon.home class="size-5" />
                                        </x-slot>

                                        <x-chief::form.input.text
                                            id="slug"
                                            wire:model="form.{{ $site->locale }}.slug"
                                        />
                                    </x-chief::form.input.prepend-append>
                                </x-chief::form.fieldset>
                            </div>

                            <div @class(['w-full', 'sm:w-1/2' => count($contexts) > 1])>
                                <x-chief::form.fieldset rule="status">
                                    <x-chief::form.label for="status">Status</x-chief::form.label>
                                    <x-chief::form.input.select
                                        id="status"
                                        wire:model="form.{{ $site->locale }}.status"
                                    >
                                        @foreach (\Thinktomorrow\Chief\Site\Urls\LinkStatus::options() as $optionValue => $optionLabel)
                                            <option
                                                wire:key="status-option-{{ $optionValue }}"
                                                value="{{ $optionValue }}"
                                            >
                                                {{ $optionLabel }}
                                            </option>
                                        @endforeach
                                    </x-chief::form.input.select>
                                </x-chief::form.fieldset>
                            </div>

                            @if (count($contexts) > 1)
                                <div class="w-full sm:w-1/2">
                                    <x-chief::form.fieldset rule="context">
                                        <x-chief::form.label for="context">Versie pagina opbouw</x-chief::form.label>
                                        <x-chief::form.input.select
                                            id="context"
                                            wire:model="form.{{ $site->locale }}.context"
                                        >
                                            @foreach ($contexts as $context)
                                                <option
                                                    wire:key="context-option-{{ $context->id }}"
                                                    value="{{ $context->id }}"
                                                >
                                                    {{ $context->title }}
                                                </option>
                                            @endforeach
                                        </x-chief::form.input.select>
                                    </x-chief::form.fieldset>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </x-chief::callout>
    @endforeach
</div>
