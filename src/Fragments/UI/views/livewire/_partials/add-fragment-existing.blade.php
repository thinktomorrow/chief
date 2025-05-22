<div>
    <div class="gutter-3 flex flex-wrap items-start justify-start">
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

    <div data-sidebar-component="existingFragments" class="border-grey-100 -mx-2 mt-6 space-y-2 border-t pt-4">
        @forelse ($this->getShareableFragments() as $shareableFragment)
            <button
                type="button"
                wire:click="attachFragment('{{ $shareableFragment->getFragmentId() }}')"
                class="group hover:bg-grey-50 flex w-full items-start gap-2 rounded-xl p-2 text-left"
            >
                <div class="text-grey-400 group-hover:text-primary-500 shrink-0 *:size-6">
                    {!! $shareableFragment->getIcon() !!}
                </div>

                <div class="grow space-y-2">
                    <h3 class="text-grey-800 group-hover:text-grey-950 text-base/6 font-medium">
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
