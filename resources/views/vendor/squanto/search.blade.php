@php
    $searchTerm = $searchTerm ?? '';
    $results = $results ?? [];
@endphp

<x-chief::page.template title="Vaste teksten" container="md">
    <form data-slot="window" method="GET" action="{{ route('squanto.index') }}" class="flex items-center gap-1">
        <x-chief::form.input.search
            name="search"
            value="{{ $searchTerm }}"
            placeholder="Zoek in vaste teksten ..."
            autofocus
        />

        <x-chief::button href="{{ route('squanto.index', ['reset' => 1]) }}" variant="transparent" size="sm">
            Reset
        </x-chief::button>

        <x-chief::button type="submit" variant="grey">
            <span>Zoek</span>
        </x-chief::button>
    </form>

    <x-chief::window>
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
                        $segments = preg_split('/(' . preg_quote($searchTerm, '/') . ')/iu', $matchingText, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY) ?: [$matchingText];

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
                        'pt-2.5' => ! $loop->first,
                        'pb-2.5' => ! $loop->last,
                    ])
                >
                    <div class="space-y-1.5">
                        <div class="flex gap-1">
                            <a
                                href="{{ route('squanto.edit', $slug) }}#{{ $result->id() }}"
                                title="{{ $key }}"
                                class="text-grey-800 leading-6 hover:underline hover:underline-offset-2"
                            >
                                {{ ucfirst($slug) }}
                            </a>

                            <x-chief::badge variant="grey" size="sm">{{ $key }}</x-chief::badge>
                        </div>

                        @if ($matchingText)
                            <div class="text-grey-500 [&_strong]:text-grey-800 body text-sm [&_strong]:font-semibold">
                                {!! $highlightedMatchingText !!}
                            </div>
                        @endif
                    </div>

                    <x-chief::button
                        href="{{ route('squanto.edit', $slug) }}#{{ $result->id() }}"
                        title="Bewerk"
                        variant="grey"
                        size="sm"
                    >
                        <x-chief::icon.quill-write />
                    </x-chief::button>
                </div>
            @empty
                <div class="text-grey-500 py-2 text-sm">Geen resultaten voor "{{ $searchTerm }}".</div>
            @endforelse
        </div>
    </x-chief::window>
</x-chief::page.template>
