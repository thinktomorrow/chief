@props([
    'size' => 'base',
    'variant' => 'grey',
    'title' => null,
])

{{--
    Options
    - size: base, sm, xs
    - variant: grey, blue, red, orange, green, outline-white
    - edge-to-edge: true, false
    
    Thoughts:
    - Header instead of title? For badges etc.
    - Other icon than chevron?
    - Make it so when you click the header/chevron, other toggles close (optional)
    - Maybe better to have a different styling for this?
--}}

<div
    x-data="{ isOpen: false }"
    {{
        $attributes->class([
            'callout',
            match ($size) {
                'base' => 'callout-base',
                'sm' => 'callout-sm',
                'xs' => 'callout-xs',
                default => 'callout-base',
            },
            match ($variant) {
                'blue' => 'callout-blue',
                'red' => 'callout-red',
                'orange' => 'callout-orange',
                'green' => 'callout-green',
                'grey' => 'callout-grey',
                'outline-white' => 'callout-outline-white',
                default => 'callout-blue',
            },
        ])
    }}
>
    <div class="grow">
        @if ($title)
            <p data-slot="title">
                {{ $title }}
            </p>
        @endif

        <div data-slot="content" class="prose" x-show="isOpen">
            {{ $slot }}
        </div>
    </div>

    <div data-slot="icon-container" x-on:click="isOpen = !isOpen">
        <x-chief::icon.chevron-left x-show="!isOpen" />
        <x-chief::icon.chevron-down x-show="isOpen" />
    </div>
</div>
