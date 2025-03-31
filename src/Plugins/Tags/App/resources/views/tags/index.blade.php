<x-chief::page.template title="Tags" container="md">
    @foreach ($tagGroups as $tagGroup)
        @php
            $tagsByGroup = $tags->filter(function ($tag) use ($tagGroup) {
                return $tag->getTagGroupId() == $tagGroup->getTagGroupId();
            });
        @endphp

        <x-chief::window>
            @if ($tagGroups->count() > 1)
                <div id="taggroup-{{ $tagGroup->getTagGroupId() }}" class="mb-2 flex items-start justify-between">
                    <div class="flex items-center gap-2">
                        <span class="body text-grey-500">
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

            <div class="rounded-xl border border-grey-200">
                <table class="min-w-full table-fixed divide-y divide-grey-200">
                    <tbody class="divide-y divide-grey-200">
                        @forelse ($tagsByGroup as $tag)
                            <tr>
                                <td class="py-1.5 pl-3 text-left">
                                    <div class="flex min-h-6 items-center gap-1.5">
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
                                    </div>
                                </td>
                                <td class="py-1.5 pl-3 text-left">
                                    <div class="flex min-h-6 items-center gap-1.5 text-sm text-grey-500">
                                        @if ($tag->getUsages() > 0)
                                            <p>In gebruik op {{ $tag->getUsages() }} pagina's</p>
                                        @else
                                            <p>Niet in gebruik</p>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-1.5 pr-3 text-right">
                                    <div class="-mr-2 flex justify-end gap-2">
                                        <x-chief::button
                                            href="{{ route('chief.tags.edit', $tag->getTagId()) }}"
                                            variant="grey"
                                            size="sm"
                                        >
                                            <x-chief::icon.quill-write />
                                        </x-chief::button>

                                        @include('chief-tags::tags.delete')
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="py-1.5 pl-3 text-left">
                                    <div class="flex min-h-[30px] items-center">
                                        <span class="text-grey-400">Deze groep bevat nog geen tags</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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
            <h2 class="h6 h6-dark">Nog geen enkele tag te zien... 😭</h2>

            <div>
                <x-chief::button href="{{ route('chief.tags.create') }}" title="Tag toevoegen">
                    <x-chief::icon.plus-sign />
                    <span>Jouw eerste tag toevoegen</span>
                </x-chief::button>
            </div>

            <p class="body text-sm text-grey-500">
                Tags houden jouw paginabeheer overzichtelijk.
                <br />
                Gebruik ze om jouw werk te stroomlijnen en het paginaoverzicht te filteren.
            </p>
        </div>
    @endif
</x-chief::page.template>
