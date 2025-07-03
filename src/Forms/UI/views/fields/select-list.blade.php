@php
    $modelBindingType = $getWireModelType() == 'defer' ? 'wire:model' : 'wire:model.' . $getWireModelType();
    $modelBinding = [$modelBindingType => Thinktomorrow\Chief\Forms\Fields\FieldName\LivewireFieldName::get($getName($locale ?? null))];
@endphp

<x-chief-form::select-list
    :options="$getMultiSelectFieldOptions()"
    :multiple="$allowMultiple()"
    :selection="$getActiveValue($locale ?? null)"
    :grouped="$hasOptionGroups()"
    :attributes="$attributes
            ->merge($getCustomAttributes())
            ->merge($modelBinding)"
/>

