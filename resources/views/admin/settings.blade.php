<x-chief::page.template title="Instellingen" container="md">
    <x-slot name="header">
        <x-chief::page.header>
            <x-slot name="actions">
                <x-chief::button form="updateForm" type="submit" variant="blue">Wijzigingen opslaan</x-chief::button>
            </x-slot>
        </x-chief::page.header>
    </x-slot>

    @php
        $fieldsWithLocales = collect($fields)->filter(fn ($field) => $field->hasLocales())->all();
        $fieldsWithoutLocales = collect($fields)->filter(fn ($field) => !$field->hasLocales())->all();
    @endphp

    <form action="{{ route('chief.back.settings.update') }}" id="updateForm" method="POST" role="form">
        @csrf
        @method('put')

        <x-chief::window title="Algemene instellingen" class="mt-4">
            @foreach ($fieldsWithoutLocales as $field)
                {!! $field->render() !!}
            @endforeach
        </x-chief::window>


        <div class="mt-6">
            <x-chief::tabs :show-nav="true" :should-listen-for-external-tab="false" reference="settings-site-toggle">
                @foreach (\Thinktomorrow\Chief\Sites\ChiefSites::locales() as $locale)
                    <x-chief::tabs.tab tab-id="{{ $locale }}"
                                       tab-label="{{ \Thinktomorrow\Chief\Sites\ChiefSites::name($locale) }}"></x-chief::tabs.tab>
                @endforeach
            </x-chief::tabs>

            <x-chief::window title="Instellingen per site">

                @foreach ($fieldsWithLocales as $field)
                    {!! $field->render() !!}
                @endforeach
            </x-chief::window>
        </div>

        <div class="mt-6">
            <x-chief::button type="submit" variant="blue">Wijzigingen opslaan</x-chief::button>
        </div>

    </form>
</x-chief::page.template>
