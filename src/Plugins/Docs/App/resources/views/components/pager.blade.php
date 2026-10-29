@props ([
    'previous' => null,
    'next' => null,
])

@if ($previous || $next)
    <nav aria-label="Handleiding doorbladeren" class="border-grey-100 grid grid-cols-2 gap-4 border-t pt-8">
        @if ($previous)
            <a href="{{ route('chief.docs.show', $previous->id) }}" class="group flex flex-col items-start gap-2">
                <x-chief::badge size="sm" class="group-hover:badge-blue">&larr; Vorige</x-chief::badge>
                <span class="text-grey-950 group-hover:text-primary-500 font-medium">{{ $previous->title }}</span>
            </a>
        @endif

        @if ($next)
            <a
                href="{{ route('chief.docs.show', $next->id) }}"
                class="group col-start-2 flex flex-col items-end gap-2 text-right"
            >
                <x-chief::badge size="sm" class="group-hover:badge-blue">Volgende &rarr;</x-chief::badge>
                <span class="text-grey-950 group-hover:text-primary-500 font-medium">{{ $next->title }}</span>
            </a>
        @endif
    </nav>
@endif
