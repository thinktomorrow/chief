<div class="flex justify-end gap-2">
    <x-chief::tabs :listen-for-external-tab="true" class="-mb-3 mt-1">
        @foreach ($sites as $site)
            <x-chief::tabs.tab wire:key="{{ $site->id }}" tab-id="{{ $site->id }}"
                               tab-label="{{ $site->shortName }}"></x-chief::tabs.tab>
        @endforeach
    </x-chief::tabs>
</div>


