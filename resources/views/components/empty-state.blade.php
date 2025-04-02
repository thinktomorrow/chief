@props([
    'title' => 'Geen resultaten gevonden',
    'icon' => null,
    'actions' => null,
])

<div {{ $attributes->class(['mx-auto max-w-2xl space-y-3 py-4 text-center']) }}>
    @if ($icon)
        <div class="*:inline *:size-10 *:text-grey-500">
            {{ $icon }}
        </div>
    @endif

    @if ($title || $slot->isNotEmpty())
        <div class="space-y-1">
            @if ($title)
                <h2 class="font-medium text-grey-950">
                    {{ $title }}
                </h2>
            @endif

            <p class="body text-balance text-sm text-grey-500">
                {{ $slot }}
            </p>
        </div>
    @endif

    @if ($actions)
        <div class="flex items-start justify-center gap-2">
            {{ $actions }}
        </div>
    @endif
</div>
