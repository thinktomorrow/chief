<div class="divide-y divide-grey-100">
    @foreach ($this->menus as $i => $menu)
        <div wire:key="menu-{{ $menu->id }}" class="space-y-3 px-4 py-6">
            <div class="flex items-start justify-between gap-2">
                <div class="mt-[0.1875rem] flex items-center gap-2">
                    <h3 class="text-sm/6 font-medium text-grey-500">{{ $menu->title }}</h3>
                </div>

                @if ($this->queuedForDeletion($menu->id))
                    <x-chief::button
                        x-on:click="$wire.undoDeleteMenu('{{ $menu->id }}')"
                        variant="grey"
                        size="sm"
                    >
                        <x-chief::icon.arrow-turn-backward />
                        <span>Ongedaan maken</span>
                    </x-chief::button>
                @else
                    <x-chief::button
                        x-on:click="$wire.deleteMenu('{{ $menu->id }}')"
                        variant="grey"
                        size="sm"
                    >
                        <x-chief::icon.delete />
                    </x-chief::button>
                @endif
            </div>

            @if (! $this->queuedForDeletion($menu->id))
                <div>
                    <div class="row-start-start gutter-3">
                        <div class="w-full">
                            <x-chief::form.fieldset rule="title">
                                <x-chief::form.label for="title">Interne titel</x-chief::form.label>
                                <x-chief::input.text id="title" wire:model="form.{{ $menu->id }}.title" />
                            </x-chief::form.fieldset>
                        </div>

                        <div class="w-full">
                            <x-chief::form.fieldset rule="locales">
                                <x-chief::form.label for="locales">In welke talen wens je de menu items te voorzien
                                </x-chief::form.label>
                                <x-chief::multiselect
                                    wire:model="form.{{ $menu->id }}.locales"
                                    :multiple="true"
                                    :options="$this->getAvailableLocales()"
                                    :selection="old('locales', $menu->locales)"
                                />
                            </x-chief::form.fieldset>
                        </div>

                    </div>
                </div>
            @endif
        </div>
    @endforeach
</div>
