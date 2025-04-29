<x-chief::tabs :show-nav="true" :should-listen-for-external-tab="false" reference="file-edit-site-toggle">
    @foreach (\Thinktomorrow\Chief\Sites\ChiefSites::locales() as $locale)
        <x-chief::tabs.tab tab-id="{{ $locale }}"
                           tab-label="{{ \Thinktomorrow\Chief\Sites\ChiefSites::name($locale) }}"></x-chief::tabs.tab>
    @endforeach
</x-chief::tabs>
