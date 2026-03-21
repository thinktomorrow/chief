@php
    $searchTerm = $searchTerm ?? '';
    $results = $results ?? [];
@endphp

<x-chief::page.template title="Vaste teksten" container="md">
    <x-chief::window>
        <form method="GET" action="{{ route('squanto.index') }}" class="mb-6 flex items-center gap-2">
            <div class="form-light w-full">
                <x-chief::form.input.text name="search" value="{{ $searchTerm }}" placeholder="Zoek in teksten"
                                          autofocus />
            </div>

            <x-chief::button type="submit" variant="grey" size="sm">
                <x-chief::icon.search />
                <span>Zoek</span>
            </x-chief::button>

            <x-chief::button href="{{ route('squanto.index', ['reset' => 1]) }}" variant="transparent" size="sm">
                Reset
            </x-chief::button>
        </form>

        <div class="divide-grey-100 divide-y">
            @forelse ($results as $result)
                @php
                    $key = $result->key();
                    $slug = \Illuminate\Support\Str::before($key, '.');
                    $label = ucfirst(str_replace('_', ' ', \Illuminate\Support\Str::afterLast($key, '.')));
                    $matchingText = null;

                    foreach (config('squanto.locales', []) as $locale) {
                        $value = (string) ($result->value($locale) ?? '');

                        if ($value !== '' && \Illuminate\Support\Str::contains(\Illuminate\Support\Str::lower($value), \Illuminate\Support\Str::lower($searchTerm))) {
                            $matchingText = $value;
                            break;
                        }
                    }

                    if (! $matchingText) {
                        $matchingText = (string) ($result->value(app()->getLocale()) ?? '');
                    }

                    $matchingText = teaser(trim(strip_tags($matchingText)), 260, '...');
                    $highlightedMatchingText = e($matchingText);

                    if ($matchingText !== '' && $searchTerm !== '') {
                        $segments = preg_split(
                            '/(' . preg_quote($searchTerm, '/') . ')/iu',
                            $matchingText,
                            -1,
                            PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY
                        ) ?: [$matchingText];

                        $highlightedMatchingText = collect($segments)
                            ->map(function (string $segment) use ($searchTerm) {
                                if (\Illuminate\Support\Str::lower($segment) === \Illuminate\Support\Str::lower($searchTerm)) {
                                    return '<strong>' . e($segment) . '</strong>';
                                }

                                return e($segment);
                            })
                            ->implode('');
                    }
                @endphp

                <div
                    @class([
                        'flex items-start justify-between gap-4',
                        'pt-3' => ! $loop->first,
                        'pb-3' => ! $loop->last,
                    ])
                >
                    <div class="space-y-0.5">
                        <a
                            href="{{ route('squanto.edit', $slug) }}#{{ $result->id() }}"
                            title="{{ $key }}"
                            class="text-grey-800 leading-6 hover:underline hover:underline-offset-2"
                        >
                            {{ $label }}
                        </a>

                        <div class="text-grey-400 text-xs">{{ $key }}</div>

                        @if ($matchingText)
                            <div class="text-grey-600 text-sm">{!! $highlightedMatchingText !!}</div>
                        @endif
                    </div>

                    <x-chief::button href="{{ route('squanto.edit', $slug) }}#{{ $result->id() }}" title="Bewerk"
                                     variant="grey" size="sm">
                        <x-chief::icon.quill-write />
                    </x-chief::button>
                </div>
            @empty
                <div class="text-grey-500 py-2 text-sm">Geen resultaten voor "{{ $searchTerm }}".</div>
            @endforelse
        </div>
    </x-chief::window>
</x-chief::page.template>
