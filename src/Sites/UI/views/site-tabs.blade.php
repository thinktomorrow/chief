<div class="flex justify-end gap-2">
    @if(count($sites) > 0)
        <x-chief::tabs :listen-for-external-tab="true" class="-mb-3 mt-1">
            @foreach ($sites as $site)
                <x-chief::tabs.tab wire:key="{{ $site->locale }}" tab-id="{{ $site->locale }}"
                                   tab-label="{{ $site->shortName }}"></x-chief::tabs.tab>
            @endforeach
        </x-chief::tabs>
    @endif
</div>


