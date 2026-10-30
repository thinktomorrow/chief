@props ([
    'previous' => null,
    'next' => null,
])

@if ($previous || $next)
    <nav
        aria-label="Handleiding doorbladeren"
        class="border-grey-100 grid grid-cols-2 items-start justify-between gap-4 border-t pt-8"
    >
        @if ($previous)
            <div>
                <a
                    href="{{ route('chief.docs.show', $previous->id) }}"
                    class="group bg-grey-50 hover:ring-grey-100 inline-flex items-start justify-start gap-4 rounded-lg px-3 py-2 transition-all hover:ring-2"
                >
                    <x-chief::link variant="grey" size="sm" class="mt-0.5 hover:no-underline">
                        <x-chief::icon.arrow-left class="text-grey-500 size-5" />
                        <span class="text-grey-500 text-xs/5 font-medium">Vorige</span>
                    </x-chief::link>

                    <span
                        class="body-dark group-hover:text-grey-950 leading-6 font-medium"
                        >{{ $previous->title }}</span
                    >
                </a>
            </div>
        @endif

        @if ($next)
            <div>
                <a
                    href="{{ route('chief.docs.show', $next->id) }}"
                    class="group bg-grey-50 hover:ring-grey-100 col-start-2 flex items-center justify-end gap-4 rounded-lg px-3 py-2 text-right transition-all hover:ring-2"
                >
                    <span class="body-dark group-hover:text-grey-950 font-medium">{{ $next->title }}</span>

                    <x-chief::link variant="grey" size="sm" class="hover:no-underline">
                        <span class="text-grey-500 text-xs/5 font-medium">Volgende</span>
                        <x-chief::icon.arrow-right class="text-grey-500 size-5" />
                    </x-chief::link>
                </a>
            </div>
        @endif
    </nav>
@endif
