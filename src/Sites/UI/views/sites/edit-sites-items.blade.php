<div class="divide-y divide-grey-100">
    @foreach ($this->sites as $i => $site)
        <div wire:key="{{ $site->locale }}" class="space-y-3 px-4 py-6">
            <div class="flex items-start justify-between gap-2">
                <div class="mt-[0.1875rem] flex items-center gap-2">
                    <h3 class="text-sm/6 font-medium text-grey-500">{{ $site->name }}</h3>
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
                        variant="grey"
                        size="sm"
                    >
                        <x-chief::icon.delete />
                    </x-chief::button>
                @endif
            </div>
        </div>
    @endforeach
</div>
