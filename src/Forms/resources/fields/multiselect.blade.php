<x-chief::multiselect
    name="{{ $getName($locale ?? null) . ($allowMultiple() ? '[]' : '') }}"
    :options="$getOptions()"
    :multiple="$allowMultiple()"
    :selection="$getActiveValue($locale ?? null)"
></x-chief::multiselect>
