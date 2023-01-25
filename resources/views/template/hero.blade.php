@props([
    'title' => null,
    'customTitle' => null,
])

@if ($title || $customTitle || $slot->isNotEmpty())
    <div class="container flex flex-wrap items-end justify-between gap-6 mb-8">
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
