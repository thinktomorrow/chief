@props([
    'iconLeft' => null,
    'iconRight' => null,
])

<div
    class="flex items-start gap-1 px-3 py-2 text-base font-medium leading-5 text-grey-800 hover:bg-grey-100 hover:text-grey-900 [&>svg]:size-5"
    role="menuitem"
    tabindex="-1"
>
    @if ($iconLeft)
        {!! $iconLeft !!}
    @endif

    @if ($slot->isNotEmpty() && $slot->hasActualContent())
        {{ $slot }}
    @endif

    @if ($iconRight)
        {!! $iconRight !!}
    @endif
</div>
