@php
    use Thinktomorrow\Chief\Forms\Fields\FieldName\LivewireFieldName;

    $fieldType = strtolower(class_basename($component));
    $fieldToggles = $getFieldToggles();

    $fieldSetAttributes = new \Illuminate\View\ComponentAttributeBag();

    $fieldSetAttributes = $fieldSetAttributes->merge([
        'data-field-key' => $getId($locale ?? null),
        'data-field-type' => $fieldType,
    ]);

    if (count($fieldToggles) > 0) {
        $fieldSetAttributes = $fieldSetAttributes->merge([
            'data-conditional-toggle' => json_encode($fieldToggles),
        ]);
    }

    if ($fieldType == 'hidden') {
        $fieldSetAttributes = $fieldSetAttributes->merge([
            'hidden' => true,
        ]);
    }
@endphp

<x-chief::form.fieldset wire:ignore.self :attributes="$fieldSetAttributes">
    <x-chief::form.label :required="$isRequired()" :translatable="$showsLocaleIndicatorInForm()">
        {!! $getLabel() !!}
    </x-chief::form.label>

    <x-chief::form.description>
        {!! $getDescription() !!}
    </x-chief::form.description>

    @if ($hasLocales() && count($getLocales()) == 1)
        @include($getView(), ['component' => $component, 'locale' => $getLocales()[0]])
    @elseif ($hasLocales() && count($getLocales()) > 1)
        <x-chief::tabs :show-nav="false" :should-listen-for-external-tab="true">
            @foreach ($getLocales() as $locale)
                <x-chief::tabs.tab tab-id="{{ $locale }}">
                    @include($getView(), ['component' => $component, 'locale' => $locale])
                </x-chief::tabs.tab>
            @endforeach
        </x-chief::tabs>
    @else
        @include($getView())
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
