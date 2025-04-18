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
        $scopedLocaleName = '<span class="text-grey-300 inline-flex items-center gap-0.5">';
        $scopedLocaleName .= '<span>' . \Thinktomorrow\Chief\Sites\ChiefSites::all()->find($_locale)->shortName . '</span>';
        $scopedLocaleName .= Blade::render('<x-chief::icon.zzz width="18" height="18" />');
        $scopedLocaleName .= '</span>';
        $scopedLocales[$_locale] = $scopedLocaleName;
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
    @elseif (count($scopedLocales) == 1)
        @foreach ($scopedLocales as $locale => $localeName)
            @include($getView(), ['component' => $component, 'locale' => $locale])
            @include('chief-form::fields._partials.charactercount')
        @endforeach
    @else
        <x-chief::tabs :should-listen-for-external-tab="false" :wire-ignore="$wireIgnoredTabs">
            @foreach ($scopedLocales as $locale => $localeName)
                <x-chief::tabs.tab tab-id="{{ $locale }}" tab-label="{!! $localeName !!}">
                    <div data-slot="control">
                        @include($getView(), ['component' => $component, 'locale' => $locale])
                        @include('chief-form::fields._partials.charactercount')
                    </div>

                    @if (in_array($locale, $getDormantLocales()))
                        <div data-slot="hint" class="flex flex-wrap items-center gap-1 text-sm text-grey-500">
                            <x-chief::icon.zzz class="size-5" />
                            <p>
                                Deze taal is inactief in deze versie, maar wordt elders gebruikt waar dit fragment
                                gedeeld wordt.
                            </p>
                        </div>
                    @endif

                    @if ($fallbackLocale = $getFallbackLocale($locale))
                        @php
                            $fallbackLocaleName = \Thinktomorrow\Chief\Sites\ChiefSites::all()->find($fallbackLocale)->shortName;
                        @endphp

                        @if(! $hasOwnLocaleValue($locale))
                            <div data-slot="hint" class="flex flex-wrap items-center gap-1">
                                <p class="text-sm text-grey-500">
                                    De '{{ $fallbackLocaleName }}' inhoud wordt getoond op de site als dit veld leeg is
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
                                <div class="max-w-sm space-y-2 px-3 py-1.5">
                                    <p class="text-sm text-grey-500">
                                        Als je het {{ $localeName }} veld niet invult, zal de {{ $fallbackLocaleName }}
                                        inhoud worden getoond op de {{ $localeName }} site.
                                    </p>
                                </div>
                            </x-chief::dialog.dropdown>
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
