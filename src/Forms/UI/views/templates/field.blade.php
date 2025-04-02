@php
    use Thinktomorrow\Chief\Forms\Fields\FieldName\LivewireFieldName;

    $fieldType = strtolower(class_basename($component));

    $attributes = $attributes->merge([
        'data-field-key' => $getId($locale ?? null),
        'data-field-type' => $fieldType,
    ]);

    if ($fieldToggles = $getFieldToggles()) {
        $attributes = $attributes->merge([
            'data-conditional-toggle' => json_encode($fieldToggles),
        ]);
    }

    if ($fieldType == 'hidden') {
        $attributes = $attributes->merge([
            'hidden' => true,
        ]);
    }

    $wireIgnoredTabs = $component instanceof \Thinktomorrow\Chief\Forms\Fields\File;

    $scopedLocales = [];

    foreach ($getScopedLocales() as $_locale) {
        $scopedLocales[$_locale] = \Thinktomorrow\Chief\Sites\ChiefSites::all()->find($_locale)->shortName;
    }

    foreach ($getDormantLocales() as $_locale) {
        $scopedLocales[$_locale] = '<span class="text-grey-300">' . \Thinktomorrow\Chief\Sites\ChiefSites::all()->find($_locale)->shortName . '</span>';
    }
@endphp

<x-chief::form.fieldset :attributes="$attributes">
    @if ($getLabel())
        <x-chief::form.label :required="$isRequired()">
            {{ $getLabel() }}
        </x-chief::form.label>
    @endif

    @if ($getDescription())
        <x-chief::form.description>
            {!! $getDescription() !!}
        </x-chief::form.description>
    @endif

    @if (! $hasLocales())
        @include($getView())
        @include('chief-form::fields._partials.charactercount')
    @elseif (count($getLocales()) == 1)
        @foreach ($getLocales() as $locale)
            @include($getView(), ['component' => $component, 'locale' => $locale])
            @include('chief-form::fields._partials.charactercount')
        @endforeach
    @else
        <x-chief::tabs :should-listen-for-external-tab="true" :wire-ignore="$wireIgnoredTabs">
            @foreach ($scopedLocales as $locale => $localeName)
                <x-chief::tabs.tab tab-id="{{ $locale }}" tab-label="{!! $localeName !!}">
                    <div data-slot="control">
                        @include($getView(), ['component' => $component, 'locale' => $locale])
                        @include('chief-form::fields._partials.charactercount')
                    </div>

                    @if ($fallbackLocale = $getFallbackLocale($locale))
                        @php
                            $fallbackLocaleName = \Thinktomorrow\Chief\Sites\ChiefSites::all()->find($fallbackLocale)->shortName;
                        @endphp

                        @if (! $hasOwnLocaleValue($locale))
                            <div data-slot="hint" class="flex items-start gap-1">
                                <p class="mt-0.5 text-sm text-grey-500">
                                    De {{ $fallbackLocaleName }} versie wordt getoond op de {{ $localeName }} site
                                </p>

                                <x-chief::button
                                    type="button"
                                    size="xs"
                                    variant="transparent"
                                    tabindex="-1"
                                    x-on:click="$dispatch('open-dialog', { 'id': 'fallback-locale-dropdown-{{ $getId() }}-{{ $locale }}' })"
                                >
                                    <x-chief::icon.information-circle />
                                </x-chief::button>
                            </div>

                            <x-chief::dialog.dropdown
                                id="fallback-locale-dropdown-{{ $getId() }}-{{ $locale }}"
                                :offset="4"
                                placement="bottom-center"
                            >
                                <div class="max-w-sm space-y-3 px-3 py-1.5">
                                    <div class="space-y-1">
                                        <p class="text-sm text-grey-500">
                                            Een leeg veld betekent dat de {{ $fallbackLocaleName }} versie wordt
                                            getoond. Vul hier iets in om een eigen {{ $localeName }} versie te tonen.
                                        </p>
                                    </div>
                                </div>
                            </x-chief::dialog.dropdown>
                        @else
                            <div data-slot="hint" class="flex items-start gap-1">
                                <p class="mt-0.5 text-sm text-grey-500">
                                    Verwijder de tekst om opnieuw de {{ $fallbackLocaleName }} versie te gebruiken.
                                </p>
                            </div>
                        @endif
                    @endif
                </x-chief::tabs.tab>
            @endforeach
        </x-chief::tabs>
    @endif

    @if ($hasLocales())
        @foreach ($getLocales() as $locale)
            <x-chief::form.error :rule="LivewireFieldName::get($getId($locale ?? null))" />
            <x-chief::form.error :rule="$getId($locale)" />
        @endforeach
    @else
        <x-chief::form.error :rule="LivewireFieldName::get($getId())" />
        <x-chief::form.error :rule="$getId()" />
    @endif
</x-chief::form.fieldset>
