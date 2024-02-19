<x-chief::multiselect
    wire:model.live="filters.{{ $name }}"
{{--    id="{{ $id }}"--}}
{{--    name="{{ $name }}"--}}
    :options='$options'
    :selection='$value ?: $default'
    :multiple='$multiple'
/>

{{--<x-chief::multiselect--}}
{{--    wire:model="{{ LivewireFieldName::get($getName($locale ?? null)) }}"--}}
{{--    name="{{ $getName($locale ?? null) . ($allowMultiple() ? '[]' : '') }}"--}}
{{--    :options="$getMultiSelectFieldOptions()"--}}
{{--    :multiple="$allowMultiple()"--}}
{{--    :selection="$getActiveValue($locale ?? null)"--}}
{{--/>--}}
