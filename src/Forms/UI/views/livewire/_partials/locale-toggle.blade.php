<div data-slot="form-group">
    <x-chief::button-group size="base">
        @foreach ($locales as $locale)
            <x-chief::button-group.button
                :aria-controls="$locale"
                :aria-selected="$locale === $scopedLocale ? 'true' : 'false'"
                wire:key="form-site-toggle-{{ $locale }}"
                x-on:click="() => {
                    $dispatch('chieftab', { id: '{{ $locale }}', reference: 'form-site-toggle' });
                    $wire.onScopedToLocale('{{ $locale }}')
                }"
            >
                {{ \Thinktomorrow\Chief\Sites\ChiefSites::name($locale) }}
            </x-chief::button-group.button>
        @endforeach
    </x-chief::button-group>

    {{-- Hack to get the scoped locale tab to be active when opening the drawer --}}
    <div
        x-data="{
            scopedLocale: '{{ $scopedLocale }}',
            init() {
                this.$nextTick(() => {
                    this.$dispatch('chieftab', {
                        id: '{{ $scopedLocale }}',
                        reference: 'form-site-toggle',
                    })
                })
            },
        }"
    ></div>
</div>
