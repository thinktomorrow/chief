@props([
    'title' => null,
    'customTitle' => null,
    'breadcrumbs' => [], // should be an array of breadcrumbs
])

<div {{ $attributes->merge(['class' => 'container border-b border-grey-200']) }}>
    @include('chief::templates.page._partials.breadcrumbs', [
        'breadcrumbs' => $breadcrumbs
    ])

    @if ($title || $customTitle || $slot->isNotEmpty())
        <div class="flex items-end justify-between pb-6 gap-y-4 gap-x-6">
            @if ($customTitle)
                {{ $customTitle }}
            @else
                <h1 class="h3 h3-dark">{{ $title }}</h1>
            @endif

            @if ($slot->isNotEmpty())
                <div class="shrink-0">
                    {{ $slot }}
                </div>
            @endif
        </div>
    @endif
</div>
