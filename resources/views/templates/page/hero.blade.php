@props([
    'title' => null,
    'description' => null,
    'customTitle' => null,
    'breadcrumbs' => [],
])

<div {{ $attributes->merge(['class' => 'container mb-6']) }}>
    @include(
        'chief::templates.page._partials.breadcrumbs',
        [
            'breadcrumbs' => $breadcrumbs,
        ]
    )

    @if ($title || $description || $customTitle || $slot->isNotEmpty())
        <div class="space-y-4">
            @if ($title || $customTitle || $slot->isNotEmpty())
                <div class="flex items-end justify-between gap-x-6 gap-y-4">
                    @if ($customTitle)
                        {{ $customTitle }}
                    @else
                        <h1 class="h2 h2-dark">{{ $title }}</h1>
                    @endif

                    @if ($slot->isNotEmpty())
                        <div class="flex shrink-0 items-start gap-3">
                            {{ $slot }}
                        </div>
                    @endif
                </div>
            @endif

            @if ($description)
                <div class="prose prose-dark prose-spacing max-w-2xl">
                    <p>{!! $description !!}</p>
                </div>
            @endif
        </div>
    @endif
</div>
