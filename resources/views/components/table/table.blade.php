@props([
    'header' => null,
    'footer' => null,
    'variant' => 'card',
])

<div
    {{
        $attributes->class([
            'divide-grey-100 ring-grey-100 divide-y rounded-xl ring-1',
            'shadow-grey-500/10 rounded-xl bg-white shadow-md' => $variant === 'card',
            '' => $variant === 'transparent',
        ])
    }}
>
    @if ($header)
        {{ $header }}
    @endif

    <div class="overflow-x-auto whitespace-nowrap">
        <table class="divide-grey-100 min-w-full table-fixed divide-y">
            {{ $slot }}
        </table>
    </div>

    @if ($footer)
        {{ $footer }}
    @endif
</div>
