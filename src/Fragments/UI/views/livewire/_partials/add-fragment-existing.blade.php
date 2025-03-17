<div class="space-y-6">
    <div>
        <div class="flex items-center gap-4">
            <x-chief::multiselect
                wire:model.live="filters.owners"
                placeholder="Kies een pagina"
                :options="$this->getOwnerFilterValues()"
                class="w-1/2"
            ></x-chief::multiselect>

            <x-chief::multiselect
                wire:model.live="filters.types"
                name="types[]"
                placeholder="Kies een type"
                :options="$this->getTypeFilterValues()"
                class="w-1/2"
            ></x-chief::multiselect>
        </div>
    </div>

    <div data-sidebar-component="existingFragments" class="space-y-3">
        @forelse($this->getShareableFragments() as $shareableFragment)
            <div>
                <button
                    wire:click="attachFragment('{{ $shareableFragment->getFragmentId() }}')"
                    class="w-full p-3 space-y-3 text-left transition-all duration-75 ease-in-out border rounded-lg border-grey-100 bg-grey-50 hover:shadow-card hover:border-primary-500"
                    type="button"
                >
                    <div>
                        <span class="h6-dark h6">
                            {{ ucfirst($shareableFragment->getLabel()) }}
                        </span>
                    </div>

                    {!! $shareableFragment->renderInAdmin() !!}
                </button>
            </div>
        @empty
            <div>
                <p class="body body-dark">Geen fragmenten gevonden.</p>
            </div>
        @endforelse
    </div>
</div>
