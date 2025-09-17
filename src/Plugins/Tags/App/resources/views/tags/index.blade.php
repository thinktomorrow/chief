<x-chief::page.template title="Tags" container="md">
    @foreach ($tagGroups as $tagGroup)
        @php
            $tagsByGroup = $tags->filter(function ($tag) use ($tagGroup) {
                return $tag->getTagGroupId() == $tagGroup->getTagGroupId();
            });
        @endphp

        <x-chief::window>
            @if ($tagGroups->count() > 1)
                <div id="taggroup-{{ $tagGroup->getTagGroupId() }}" class="mb-3 flex items-start justify-between">
                    <div class="flex items-center gap-2">
                        <span class="font-display text-grey-950 text-xl/6 font-semibold">
                            {{ ucfirst($tagGroup->getLabel()) }}
                        </span>

                        @if ($tagGroup->getTagGroupId())
                            <x-chief::button
                                href="{{ route('chief.taggroups.edit', $tagGroup->getTagGroupId()) }}"
                                variant="grey"
                                size="sm"
                            >
                                <x-chief::icon.quill-write />
                            </x-chief::button>
                        @endif
                    </div>

                    <x-chief::button
                        href="{{ route('chief.tags.create') }}?taggroup_id={{ $tagGroup->getTagGroupId() }}"
                        variant="grey"
                        size="sm"
                    >
                        <x-chief::icon.plus-sign />
                        <span>Tag toevoegen</span>
                    </x-chief::button>
                </div>
            @else
                <div class="mb-2 flex items-start justify-end">
                    <x-chief::button href="{{ route('chief.tags.create') }}" variant="grey" size="sm">
                        <x-chief::icon.plus-sign />
                        <span>Tag toevoegen</span>
                    </x-chief::button>
                </div>
            @endif

            <x-chief::table variant="transparent">
                <x-chief::table.body>
                    @forelse ($tagsByGroup as $tag)
                        <x-chief::table.row>
                            <x-chief::table.cell>
                                <a
                                    href="{{ route('chief.tags.edit', $tag->getTagId()) }}"
                                    title="{{ $tag->getLabel() }}"
                                    class="inline-flex items-center gap-x-3"
                                >
                                    <svg
                                        class="h-2.5 w-2.5"
                                        style="fill: {{ $tag->getColor() }}"
                                        viewBox="0 0 6 6"
                                        aria-hidden="true"
                                    >
                                        <circle cx="3" cy="3" r="3" />
                                    </svg>

                                    <span class="body-dark font-medium hover:underline">
                                        {{ $tag->getLabel() }}
                                    </span>
                                </a>
                            </x-chief::table.cell>
                            <x-chief::table.cell>
                                @if ($tag->getUsages() > 0)
                                    <p>In gebruik op {{ $tag->getUsages() }} pagina's</p>
                                @else
                                    <p>Niet in gebruik</p>
                                @endif
                            </x-chief::table.cell>
                            <x-chief::table.cell justify="end">
                                <x-chief::button
                                    href="{{ route('chief.tags.edit', $tag->getTagId()) }}"
                                    variant="grey"
                                    size="sm"
                                >
                                    <x-chief::icon.quill-write />
                                </x-chief::button>

                                @include('chief-tags::tags.delete')
                            </x-chief::table.cell>
                        </x-chief::table.row>
                    @empty
                        <x-chief::table.row>
                            <x-chief::table.cell>
                                <div class="flex min-h-[30px] items-center">
                                    <span class="text-grey-400">Deze groep bevat nog geen tags</span>
                                </div>
                            </x-chief::table.cell>
                        </x-chief::table.row>
                    @endforelse
                </x-chief::table.body>
            </x-chief::table>
        </x-chief::window>
    @endforeach

    @if (count($tags) > 0)
        <div data-slot="window" class="flex justify-center gap-6 text-center">
            <x-chief::button href="{{ route('chief.taggroups.create') }}" variant="grey" size="sm">
                <x-chief::icon.plus-sign />
                <span>Groep toevoegen</span>
            </x-chief::button>
        </div>
    @else
        <div class="space-y-4 py-4">
            <h2 class="font-display text-grey-950 text-xl/6 font-semibold">Nog geen enkele tag te zien... 😭</h2>

            <div>
                <x-chief::button href="{{ route('chief.tags.create') }}" title="Tag toevoegen">
                    <x-chief::icon.plus-sign />
                    <span>Jouw eerste tag toevoegen</span>
                </x-chief::button>
            </div>

            <p class="body text-grey-500 text-sm">
                Tags houden jouw paginabeheer overzichtelijk.
                <br />
                Gebruik ze om jouw werk te stroomlijnen en het paginaoverzicht te filteren.
            </p>
        </div>
    @endif
</x-chief::page.template>
