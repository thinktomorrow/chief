<div class="flex justify-end gap-2">
    @if (count($sites) > 0)
        <x-chief::tabs :listen-for-external-tab="true" size="base" :show-tabs="false">
            @foreach ($sites as $site)
                <x-chief::tabs.tab
                    wire:key="{{ $site->id }}"
                    tab-id="{{ $site->id }}"
                    tab-label="{{ $site->shortName }}"
                />
            @endforeach
        </x-chief::tabs>
    @endif
</div>
