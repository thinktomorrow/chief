<div class="card p-4 space-y-4">

    @if(!$inEditMode)

        <div class="w-full">
            @if(count($siteLinks = $this->getActiveSiteLinks()) > 0)
                @foreach($siteLinks as $siteLink)
                    <div class="w-full flex gap-2 items-center">
                        <span class="label label-primary label-xs">{{ $siteLink->status->value }}</span>
                        <span>{{ $siteLink->url->path }}</span>
                        @if($siteLink->contextId)
                            <span>{{ $siteLink->contextTitle }}</span>
                        @endif
                    </div>
                @endforeach
            @else
                <span class="text-sm text-grey-600 py-1 px-2">geen talen actief</span>
            @endif
        </div>

        <x-chief::button wire:click="$set('inEditMode', true)" class="cursor-pointer text-xs">
            edit
        </x-chief::button>
    @else

        <h2>Links bewerken</h2>

        <div class="w-full">
            @foreach($this->getAllSiteLinks() as $siteLink)
                <div class="w-full flex gap-2 items-center">

                    <x-chief::input.select wire:model.live="siteLinks.{{ $siteLink->siteId }}">
                        <option value="">---</option>
                        @foreach($this->getLinkStatusOptions() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </x-chief::input.select>

                    <span class="label label-primary label-xs">{{ $siteLink->status->value }}</span>
                    <span>{{ $siteLink->url?->path }}</span>
                    @if($siteLink->contextId)
                        <span>{{ $siteLink->contextTitle }}</span>
                    @endif
                </div>
            @endforeach
        </div>

        <x-chief::button wire:click="save" class="cursor-pointer text-xs">
            Bewaren
        </x-chief::button>
    @endif

</div>


