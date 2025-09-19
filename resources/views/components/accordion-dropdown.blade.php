@props([
    'title' => null,
    'description' => null,
    'collapsible' => true,
    'isOpen' => true,
])

<div
    x-data="{
        accordionDropdownIsCollapsible: {{ $collapsible ? 'true' : 'false' }},
        accordionDropdownIsOpen: {{ $isOpen ? 'true' : 'false' }},
    }"
    {{ $attributes->class('divide-grey-100 bg-grey-50 divide-y rounded-xl') }}
>
    <div class="flex items-start justify-between gap-2 px-4 py-3.5">
        <div class="mt-[0.1875rem] space-y-1.5">
            @if ($title)
                <p class="font-display text-grey-900 text-lg/6 font-medium">
                    {{ $title }}
                </p>
            @endif

            @if ($description)
                <p class="text-grey-500 text-base/6">
                    {!! $description !!}
                </p>
            @endif
        </div>

        @if ($collapsible)
            <x-chief::button
                x-on:click="accordionDropdownIsOpen = !accordionDropdownIsOpen"
                size="sm"
                variant="transparent"
                class="shrink-0"
            >
                <span data-slot="icon">
                    <x-chief::icon.chevron-left x-show="!accordionDropdownIsOpen" />
                    <x-chief::icon.chevron-down x-show="accordionDropdownIsOpen" />
                </span>
            </x-chief::button>
        @endif
    </div>

    @if ($slot->isNotEmpty())
        <div
            x-show="
                (accordionDropdownIsCollapsible && accordionDropdownIsOpen) ||
                    ! accordionDropdownIsCollapsible
            "
            class="px-4 py-3.5"
        >
            {{ $slot }}
        </div>
    @endif
</div>
