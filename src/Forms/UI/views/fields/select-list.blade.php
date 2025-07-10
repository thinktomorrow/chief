<x-chief-form::select-list
    wire:ignore
    :options="$getMultiSelectFieldOptions()"
    :multiple="$allowMultiple()"
    :selection="$getActiveValue($locale ?? null)"
    :grouped="$hasOptionGroups()"
    :attributes="$attributes
            ->merge($getCustomAttributes())
            ->merge([$getWireModelType() => $getWireModelValue($locale ?? null)])"
/>

