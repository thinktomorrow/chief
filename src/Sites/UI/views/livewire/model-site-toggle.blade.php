<div class="space-y-6">
    <x-chief::button-group size="base">
        @foreach ($sites as $site)
            <x-chief::button-group.button
                aria-controls="{{ $site->locale }}"
                aria-selected="{{ $site->locale === $scopedLocale ? 'true' : 'false' }}"
                wire:key="model-site-toggle-{{ $site->locale }}"
                wire:click="set('scopedLocale', '{{ $site->locale }}')"
                :active="$site->locale == $scopedLocale"
            >
                {{ \Thinktomorrow\Chief\Sites\ChiefSites::name($site->locale) }}
            </x-chief::button-group.button>
        @endforeach
    </x-chief::button-group>
</div>
