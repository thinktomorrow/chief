<div class="flex justify-end gap-2">
    @if (count($sites) > 0)
        <x-chief::tabs :should-listen-for-external-tab="true" size="base" :show-tabs="false">
            @foreach ($sites as $site)
                <x-chief::tabs.tab
                    wire:key="{{ $site->locale }}"
                    tab-id="{{ $site->locale }}"
                    tab-label="{{ $site->shortName }}"
                />
            @endforeach
        </x-chief::tabs>
    @endif
</div>
