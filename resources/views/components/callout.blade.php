@props([
    'size' => 'base',
    'variant' => 'grey',
    'title' => null,
    'icon' => null,
])

<div
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
    @if ($icon)
        <div {{ $icon->attributes->merge(['data-slot' => 'icon-container']) }}>
            {{ $icon }}
        </div>
    @endif

    <div class="grow">
        @if ($title)
            <p data-slot="title">
                {{ $title }}
            </p>
        @endif

        <div data-slot="content" class="prose-format prose-editor">
            {{ $slot }}
        </div>
    </div>
</div>
