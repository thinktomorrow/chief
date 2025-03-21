@props([
    'title' => null,
    'actions' => null,
    'customTitle' => null,
    'breadcrumbs' => [],
])

@aware(['title'])

<div {{ $attributes->class('space-y-3') }}>
    @include('chief::templates.page._partials.breadcrumbs', ['breadcrumbs' => $breadcrumbs])

    @if ($title || $customTitle || $actions)
        <div class="space-y-4">
            @if ($title || $customTitle || $slot->isNotEmpty())
                <div class="flex items-start justify-between gap-x-12 gap-y-4 max-xl:flex-wrap">
                    <div class="max-w-4xl">
                        @if ($customTitle)
                            {{ $customTitle }}
                        @else
                            <h1 class="h1 h1-dark">{{ $title }}</h1>
                        @endif
                    </div>

                    @if ($actions)
                        <div {{ $actions->attributes->class('ml-auto mt-0.5 flex shrink-0 items-start gap-2') }}>
                            {{ $actions }}
                        </div>
                    @endif
                </div>
            @endif

            @if ($slot->isNotEmpty())
                <div class="prose prose-dark prose-spacing max-w-2xl">
                    {{ $slot }}
                </div>
            @endif
        </div>
    @endif
</div>
