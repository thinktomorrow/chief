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
            @foreach ($getLocales() as $locale)
                @php
                    $fallbackLocale = 'EN';
                    $hasFallbackLocale = $loop->first;
                    $activeFallbackLocale = false;
                @endphp

                <x-chief::tabs.tab tab-id="{{ $locale }}">
                    <div data-slot="control">
                        @include($getView(), ['component' => $component, 'locale' => $locale])
                        @include('chief-form::fields._partials.charactercount')
                    </div>

                    @if ($hasFallbackLocale)
                        <div class="mt-2 flex items-start gap-1">
                            <p class="mt-0.5 text-sm text-grey-500">Fallback locale overschreven</p>

                            <x-chief::button
                                type="button"
                                size="xs"
                                variant="transparent"
                                x-on:click="$dispatch('open-dialog', { 'id': 'fallback-locale-dropdown-{{ $getId() }}-{{ $locale }}' })"
                            >
                                <x-chief::icon.solid.information-diamond />
                            </x-chief::button>
                        </div>

                        <x-chief::dialog.dropdown
                            id="fallback-locale-dropdown-{{ $getId() }}-{{ $locale }}"
                            :offset="4"
                            placement="bottom-center"
                        >
                            <div class="max-w-sm space-y-3 px-3 py-1.5">
                                <div class="space-y-1">
                                    <p class="body-dark text-sm font-medium">Wat is een fallback locale?</p>

                                    <p class="text-sm text-grey-500">
                                        Een fallback locale is een locale die gebruikt wordt als de huidige locale niet
                                        ingevuld is.
                                    </p>
                                </div>

                                <x-chief::callout size="sm" variant="grey" title="Fallback locale resetten">
                                    <p class="text-sm text-grey-500">
                                        Je hebt de fallback locale van deze locale overschreven. Je kunt deze
                                        terugzetten naar de fallback locale als je wil.
                                    </p>

                                    <x-chief::button type="button" size="sm" variant="outline-white" class="mt-2">
                                        Gebruik fallback locale
                                    </x-chief::button>
                                </x-chief::callout>
                            </div>
                        </x-chief::dialog.dropdown>
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
