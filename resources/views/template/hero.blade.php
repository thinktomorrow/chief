@props([
    'title' => null,
    'customTitle' => null,
    'breadcrumbs' => [], // should be an array of breadcrumbs
])

<div {{ $attributes->merge(['class' => 'container space-y-2']) }}>
    @include('chief::template._partials.breadcrumbs', [
        'breadcrumbs' => $breadcrumbs
    ])

    @if ($title || $customTitle || $slot->isNotEmpty())
        <div class="flex flex-wrap items-end justify-between gap-6 pb-8">
            @if ($customTitle)
                {{ $customTitle }}
            @else
                <h1 class="h1 display-dark">{{ $title }}</h1>
            @endif

            @if ($slot->isNotEmpty())
                <div>
                    {{ $slot }}
                </div>
            @endif
        </div>
    @endif
</div>
