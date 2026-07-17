<x-chief::page.template title="Vaste teksten">
    <form data-slot="window" method="GET" action="{{ route('squanto.index') }}" class="flex items-center gap-1">
        <x-chief::form.input.search name="search" placeholder="Zoek in vaste teksten ..." autofocus />

        <x-chief::button type="submit" variant="grey">
            <span>Zoek</span>
        </x-chief::button>
    </form>

    @foreach ($pageGroups as $sourceLabel => $groupedPages)
        <section data-slot="window" class="space-y-3">
            <h2 class="font-display text-grey-950 text-lg font-semibold">{{ $sourceLabel }}</h2>

            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
                @foreach ($groupedPages as $page)
                    @php
                        $lines = app(\Thinktomorrow\Squanto\Database\DatabaseLinesRepository::class)->allForPage($page->pageKey());
                        $locales = config('squanto.locales');

                        $totalTranslations = $lines->count() * count($locales);
                        $filledTranslations = 0;
                        $sectionKeys = [];

                        $lines->each(function ($line) use ($locales, &$filledTranslations, &$sectionKeys) {
                            $segments = explode('.', $line->keyAsString());

                            if (isset($segments[1])) {
                                $sectionKeys[$segments[1]] = true;
                            }

                            foreach ($locales as $locale) {
                                if ($line->value($locale) !== null && $line->value($locale) !== '') {
                                    $filledTranslations++;
                                }
                            }
                        });

                        $sectionsCount = count($sectionKeys);

                        $percentage = $totalTranslations > 0
                            ? (int) round($filledTranslations / $totalTranslations * 100)
                            : 100;
                    @endphp

                    <a
                        class="border-grey-100 shadow-grey-500/10 group hover:border-grey-200 flex flex-col overflow-hidden rounded-xl border bg-white shadow-md"
                        href="{{ route('squanto.edit', $page->slug()) }}"
                        title="Bewerk {{ ucfirst($page->label()) }}"
                    >
                        <div class="space-y-2.5 p-3">
                            <div class="flex items-start justify-between gap-1.5">
                                <span
                                    class="text-grey-800 font-display min-w-0 truncate leading-6 font-medium group-hover:underline group-hover:underline-offset-2"
                                >
                                    {{ ucfirst($page->label()) }}
                                </span>

                                @if ($sectionsCount > 0)
                                    <x-chief::badge :variant="$percentage == 100 ? 'green' : 'orange'" size="sm">
                                        {{ $percentage }}%
                                    </x-chief::badge>
                                @endif
                            </div>

                            <div class="flex items-center justify-between gap-2">
                                <span class="text-grey-500 body text-sm leading-5">
                                    {{ $sectionsCount }} {{ $sectionsCount == 1 ? 'sectie' : 'secties' }} · {{ $totalTranslations }} {{ $totalTranslations == 1 ? 'veld' : 'velden' }}
                                </span>
                            </div>
                        </div>

                        <div class="bg-grey-100 mt-auto h-1">
                            @if ($sectionsCount > 0)
                                <div
                                    @class ([
                                    'h-full',
                                    'bg-linear-to-r from-green-500 to-green-400' => $percentage == 100,
                                    'bg-linear-to-r from-orange-400 to-orange-300' => $percentage < 100 && $percentage >= 50,
                                    'bg-linear-to-r from-red-500 to-red-400' => $percentage < 50,
                                ])
                                    style="width: {{ $percentage }}%"
                                ></div>
                            @else
                                <div class="bg-grey-200 h-full"></div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endforeach
</x-chief::page.template>
