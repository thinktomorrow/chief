<div class="divide-y divide-grey-100">
    @foreach ($this->sites as $i => $site)
        <div wire:key="{{ $site->locale }}" class="space-y-3 px-4 py-6">
            <div class="flex items-start justify-between gap-2">
                <div class="mt-[0.1875rem] flex items-center gap-2">
                    <h3 class="text-sm/6 font-medium text-grey-500">{{ $site->site->name }}</h3>

                    <x-chief::badge variant="green" size="sm">
                        {{ $site->status->value }}
                    </x-chief::badge>
                </div>

                @if ($this->queuedForDeletion($site->locale))
                    <x-chief::button x-on:click="$wire.undoDeleteSite('{{ $site->locale }}')" variant="grey" size="sm">
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
                <div>
                    <div class="row-start-start gutter-3">
                        <div class="w-full">
                            <x-chief::form.fieldset rule="slug">
                                <x-chief::form.label for="slug" required>Slug</x-chief::form.label>
                                <x-chief::form.input.prepend-append :prepend="$site->site->url">
                                    <x-chief::form.input.text id="slug" wire:model="form.{{ $site->locale }}.slug" />
                                </x-chief::form.input.prepend-append>
                            </x-chief::form.fieldset>
                        </div>

                        <div class="w-full lg:w-1/2">
                            <x-chief::form.fieldset rule="status">
                                <x-chief::form.label for="status">Status</x-chief::form.label>
                                <x-chief::form.input.select id="status" wire:model="form.{{ $site->locale }}.status">
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
                            <div class="w-full lg:w-1/2">
                                <x-chief::form.fieldset rule="context">
                                    <x-chief::form.label for="context">Context</x-chief::form.label>
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
    @endforeach
</div>
