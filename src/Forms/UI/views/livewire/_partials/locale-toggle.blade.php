<div
    x-data="{
        scopedLocale: '{{ $scopedLocale }}',
        init() {
            this.$nextTick(() => {
                this.showScopedLocale();
            })
        },
        showScopedLocale() {
            this.$dispatch('chieftab', { id: this.scopedLocale, reference: 'form-site-toggle' });

            @if ($entangleScopedLocale ?? false)
                $wire.onScopedToLocale(this.scopedLocale);
            @endif

        }
    }"
    data-slot="form-group"
>
    <span class="font-bold" x-text="scopedLocale"></span>
    <span>{{ implode(",", $locales) }}</span>

    @if (count($locales) > 1)
        <x-chief::button-group size="base">
            @foreach ($locales as $i => $locale)
                <x-chief::button-group.button
                    :aria-controls="$locale"
                    :aria-selected="($locale === $scopedLocale) ? 'true' : 'false'"
                    wire:key="form-site-toggle-{{ $locale }}"
                    x-on:click="() => {
                        scopedLocale = '{{ $locale }}';
                        showScopedLocale();
                    }"
                >
                    {{ \Thinktomorrow\Chief\Sites\ChiefSites::name($locale) }}
                </x-chief::button-group.button>
            @endforeach
        </x-chief::button-group>
    @endif
</div>
