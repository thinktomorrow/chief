<div>
    <div class="row-start-start gutter-3">
        <div class="w-1/2">
            <x-chief::form.fieldset>
                <x-chief::form.label>Kies een pagina</x-chief::form.label>
                <x-chief::multiselect
                    wire:model.live="filters.owners"
                    placeholder="Kies een pagina"
                    :options="$this->getOwnerFilterValues()"
                />
            </x-chief::form.fieldset>
        </div>

        <div class="w-1/2">
            <x-chief::form.fieldset>
                <x-chief::form.label>Kies een type</x-chief::form.label>
                <x-chief::multiselect
                    wire:model.live="filters.types"
                    name="types[]"
                    placeholder="Kies een type"
                    :options="$this->getTypeFilterValues()"
                />
            </x-chief::form.fieldset>
        </div>
    </div>

    <div data-sidebar-component="existingFragments" class="-mx-2 mt-6 space-y-2 border-t border-grey-100 pt-4">
        @forelse ($this->getShareableFragments() as $shareableFragment)
            <button
                type="button"
                wire:click="attachFragment('{{ $shareableFragment->getFragmentId() }}')"
                class="group flex w-full items-start gap-2 rounded-xl p-2 text-left hover:bg-grey-50"
            >
                <div class="shrink-0 text-grey-400 *:size-6 group-hover:text-primary-500">
                    {!! $shareableFragment->getIcon() !!}
                </div>

                <div class="grow space-y-2">
                    <h3 class="text-base/6 font-medium text-grey-800 group-hover:text-grey-950">
                        {{ ucfirst($shareableFragment->getLabel()) }}
                    </h3>

                    @if ($adminView = $shareableFragment->renderInAdmin())
                        <div>
                            {!! $adminView !!}
                        </div>
                    @endif
                </div>
            </button>
        @empty
            <div class="px-2">
                <p class="body text-grey-500">Geen fragmenten gevonden.</p>
            </div>
        @endforelse
    </div>
</div>
