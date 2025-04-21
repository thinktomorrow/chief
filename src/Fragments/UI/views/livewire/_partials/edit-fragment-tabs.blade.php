<x-chief::tabs active-tab="{{ $scopedLocale }}" :show-nav="true" :should-listen-for-external-tab="true">
    @foreach ($locales as $locale)
        <x-chief::tabs.tab tab-id="{{ $locale }}"
                           tab-label="{{ \Thinktomorrow\Chief\Sites\ChiefSites::name($locale) }}" />
    @endforeach
</x-chief::tabs>

{{-- Hack to get the scoped locale tab to be active when opening the drawer --}}
<div x-data="{
    scopedLocale: '{{ $scopedLocale }}' ,
    init() {
        this.$nextTick(() => {
            this.$dispatch('chieftab', { id: '{{ $scopedLocale }}' });
        });
    }
}"></div>
