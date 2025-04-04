<x-chief::accordion-dropdown
    data-slot="form-group"
    :title="$getTitle()"
    :description="$getDescription()"
    :collapsible="$isCollapsible()"
    :is-open="!$isCollapsed()"
    {{ $attributes->merge($getCustomAttributes()) }}
>
    @foreach ($getComponents() as $_component)
        {{ $_component }}
    @endforeach
</x-chief::accordion-dropdown>
