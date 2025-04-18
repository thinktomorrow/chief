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

    @if($getScopedLocale())
        @include($getView(), ['component' => $component, 'locale' => $getScopedLocale()])
    @elseif(!$hasLocales())
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
