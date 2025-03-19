@foreach($this->siteLinks as $i => $siteLink)
    <div wire:key="{{ $siteLink->siteId }}">
        <div>
            @include('chief-sites::link-status-dot')

            <div>
                <p class="text-sm leading-6 text-grey-500">{{ $siteLink->site->name }}</p>
            </div>

            @if($this->queuedForDeletion($siteLink->siteId))
                <span x-on:click="$wire.undoDeleteSite('{{ $siteLink->siteId }}')">Niet verwijderen</span>
            @else
                <span x-on:click="$wire.deleteSite('{{ $siteLink->siteId }}')">Delete</span>
            @endif

        </div>

        @if(!$this->queuedForDeletion($siteLink->siteId))
            <div class="flex items-start justify-between gap-2">

                <x-chief::input.group rule="slug" class="w-full lg:w-1/2">
                    <x-chief::form.label for="slug" required>Slug</x-chief::form.label>
                    <x-chief::input.text id="slug" wire:model="form.{{ $siteLink->siteId }}.slug" />
                </x-chief::input.group>

                <x-chief::input.group rule="status" class="w-full lg:w-1/2">
                    <x-chief::input.select id="status" wire:model="form.{{ $siteLink->siteId }}.status">
                        @foreach (\Thinktomorrow\Chief\Site\Urls\LinkStatus::options() as $optionValue => $optionLabel)
                            <option wire:key="status-option-{{ $optionValue }}"
                                    value="{{ $optionValue }}">{{ $optionLabel }}</option>
                        @endforeach
                    </x-chief::input.select>
                </x-chief::input.group>

                @if(count($contexts) > 1)
                    <x-chief::input.group rule="context" class="w-full lg:w-1/2">
                        <x-chief::input.select id="context"
                                               wire:model="form.{{ $siteLink->siteId }}.context">
                            @foreach ($contexts as $context)
                                <option wire:key="context-option-{{ $context->contextId }}"
                                        value="{{ $context->contextId }}">{{ $context->label ?: 'Default' }}</option>
                            @endforeach
                        </x-chief::input.select>
                    </x-chief::input.group>
                @endif

            </div>
        @endif
    </div>
@endforeach
