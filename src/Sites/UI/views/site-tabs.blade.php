<div class="flex justify-end gap-2">

    <x-chief::tabs wire:key="{{ \Illuminate\Support\Str::random() }}" :listen-for-external-tab="true"
                   class="-mb-3 mt-1">
        @foreach ($sites as $siteId)
            <x-chief::tabs.tab wire:key="{{ \Illuminate\Support\Str::random() }}"
                               tab-id="{{ $siteId }}"></x-chief::tabs.tab>
        @endforeach
    </x-chief::tabs>

    <span wire:click="$toggle('showSettings')">aanpassen</span>

    @if($this->showSettings)
        <div>
            @foreach(\Thinktomorrow\Chief\Sites\ChiefSites::all() as $site)
                <label wire:key="site-select-key-{{ $site->id }}" for="site-select-{{ $site->id }}">
                    <x-chief::input.checkbox
                        id="site-select-{{ $site->id }}"
                        wire:model.live="sites"
                        value="{{ $site->id }}"
                    />
                    <span>{{ $site->shortName }}</span>
                </label>
            @endforeach

            <button wire:click="saveSettings">bewaren</button>
        </div>
    @endif

</div>


