<div class="space-y-6" data-slot="form-group">
    <x-chief::button-group size="base" wire:ignore>
        @foreach ($locales as $locale)
            <x-chief::button-group.button
                aria-controls="{{ $locale }}"
                aria-selected="{{ $locale === $scopedLocale ? 'true' : 'false' }}"
                wire:key="fragment-site-toggle-{{ $locale }}"
                wire:click="onScopedToLocale('{{ $locale }}')"
                :active="$locale == $scopedLocale"
            >
                {{ \Thinktomorrow\Chief\Sites\ChiefSites::name($locale) }}
            </x-chief::button-group.button>
        @endforeach
    </x-chief::button-group>
</div>
