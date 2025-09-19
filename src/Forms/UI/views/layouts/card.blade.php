@if (count($getComponents()) > 0)
    <x-chief::accordion-dropdown
        :title="$getTitle()"
        :description="$getDescription()"
        :collapsible="$isCollapsible()"
        :is-open="!$isCollapsed()"
        {{ $attributes->merge(['data-slot' => 'form-group']) }}
    >
        @foreach ($getComponents() as $_component)
            {{ $_component }}
        @endforeach
    </x-chief::accordion-dropdown>
@endif
