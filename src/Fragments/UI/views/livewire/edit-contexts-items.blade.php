<div class="divide-y divide-grey-100">
    @foreach ($this->contexts as $i => $context)
        <div wire:key="menu-{{ $context->id  }}" class="space-y-3 px-4 py-6">
            <div class="flex items-start justify-between gap-2">
                <div class="mt-[0.1875rem] flex items-center gap-2">
                    <h3 class="text-sm/6 font-medium text-grey-500">{{ $context->title }}</h3>
                </div>

                @if ($this->queuedForDeletion($context->id ))
                    <x-chief::button
                        x-on:click="$wire.undoDeleteContext('{{ $context->id  }}')"
                        variant="grey"
                        size="sm"
                    >
                        <x-chief::icon.arrow-turn-backward />
                        <span>Ongedaan maken</span>
                    </x-chief::button>
                @else
                    <x-chief::button
                        x-on:click="$wire.deleteContext('{{ $context->id  }}')"
                        variant="grey"
                        size="sm"
                    >
                        <x-chief::icon.delete />
                    </x-chief::button>
                @endif
            </div>

            @if (! $this->queuedForDeletion($context->id ))
                <div>
                    <div class="row-start-start gutter-3">
                        <div class="w-full">
                            <x-chief::form.fieldset rule="title">
                                <x-chief::form.label for="title">Titel</x-chief::form.label>
                                <x-chief::form.input.text id="title"
                                                          wire:model="form.{{ $context->id  }}.title" />
                            </x-chief::form.fieldset>
                        </div>

                        <div class="w-full">
                            <x-chief::form.fieldset rule="locales">
                                <x-chief::form.label for="locales">In welke talen wens je te gebruiken op de pagina?
                                </x-chief::form.label>
                                <x-chief::multiselect
                                    wire:model="form.{{ $context->id  }}.locales"
                                    :multiple="true"
                                    :options="$this->getAvailableLocales()"
                                    :selection="old('locales', $context->locales)"
                                />
                            </x-chief::form.fieldset>
                        </div>

                    </div>
                </div>
            @endif
        </div>
    @endforeach
</div>
