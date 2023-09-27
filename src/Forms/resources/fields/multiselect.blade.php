<x-chief::multiselect
    name="{{ $getName($locale ?? null) . ($allowMultiple() ? '[]' : '') }}"
    :options="$getMultiSelectFieldOptions()"
    :multiple="$allowMultiple()"
    :selection="$getActiveValue($locale ?? null)"
/>
