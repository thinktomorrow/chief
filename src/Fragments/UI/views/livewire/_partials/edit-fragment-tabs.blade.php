<x-chief::button-group data-slot="form-group" size="base">
    @foreach ($locales as $locale)
        <x-chief::button-group.button
            :aria-controls="$locale"
            :aria-selected="$locale === $scopedLocale ? 'true' : 'false'"
            wire:key="fragment-site-toggle-{{ $locale }}"
            x-on:click="$dispatch('chieftab', { id: '{{ $locale }}' }); $wire.onScopedToLocale('{{ $locale }}')"
        >
            {{ \Thinktomorrow\Chief\Sites\ChiefSites::name($locale) }}
        </x-chief::button-group.button>
    @endforeach
</x-chief::button-group>
