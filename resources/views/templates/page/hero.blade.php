@props([
    'title' => null,
    'description' => null,
    'customTitle' => null,
    'breadcrumbs' => [], // should be an array of breadcrumbs
])

<div {{ $attributes->merge(['class' => 'container space-y-2']) }}>
    @include('chief::templates.page._partials.breadcrumbs', [
        'breadcrumbs' => $breadcrumbs
    ])

    @if($title || $description || $customTitle || $slot->isNotEmpty())
        <div class="pb-8 space-y-4">
            @if ($title || $customTitle || $slot->isNotEmpty())
                <div class="flex items-end justify-between gap-y-4 gap-x-6">
                    @if ($customTitle)
                        {{ $customTitle }}
                    @else
                        <h1 class="h1 h1-dark">{{ $title }}</h1>
                    @endif

                    @if ($slot->isNotEmpty())
                        <div class="shrink-0">
                            {{ $slot }}
                        </div>
                    @endif
                </div>
            @endif

            @if($description)
                <div class="max-w-2xl prose prose-dark prose-spacing">
                    <p>{!! $description !!}</p>
                </div>
            @endif
        </div>
    @endif
</div>
