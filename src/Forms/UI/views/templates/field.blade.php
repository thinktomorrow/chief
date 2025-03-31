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
        <x-chief::tabs :listen-for-external-tab="true" :wire-ignore="$wireIgnoredTabs">
            @foreach ($getLocales() as $locale)
                <x-chief::tabs.tab tab-id="{{ $locale }}">
                    @include($getView(), ['component' => $component, 'locale' => $locale])
                    @include('chief-form::fields._partials.charactercount')
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
