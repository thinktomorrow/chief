<x-chief::page.template title="Tags">
    <x-slot name="hero">
        <x-chief::page.hero title="Tags" class="max-w-3xl"/>
    </x-slot>

    <x-chief::page.grid class="max-w-3xl">
        @foreach($tagGroups as $tagGroup)
            @php
                $tagsByGroup = $tags->filter(function($tag) use ($tagGroup) {
                    return $tag->getTagGroupId() == $tagGroup->getTagGroupId();
                });
            @endphp

            @continue($tagsByGroup->isEmpty())

            <x-chief::window>
                @if ($tagGroup->getLabel())
                    @if ($tagGroups->count() > 1)
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex items-center gap-1">
                                <span class="body text-grey-500">
                                    {{ ucfirst($tagGroup->getLabel()) }}
                                </span>

                                <a href="{{ route('chief.taggroups.edit', $tagGroup->getTagGroupId()) }}">
                                    <x-chief::icon-button icon="icon-edit" color="grey" class="shadow-none text-grey-500">
                                        <svg width="16" height="16"><use xlink:href="#icon-edit"></use></svg>
                                    </x-chief::icon-button>
                                </a>
                            </div>

                            <a
                                href="{{ route('chief.tags.create') }}"
                                title="Tag toevoegen"
                                class="inline-flex items-center gap-0.5 mt-1 text-sm font-medium text-grey-500 hover:text-primary-500"
                            >
                                <svg width="18" height="18"> <use xlink:href="#icon-plus"></use> </svg>
                                <span>Tag toevoegen</span>
                            </a>
                        </div>
                    @endif
                @endif

                <x-chief::table>
                    <x-slot name="body">
                        @foreach ($tagsByGroup as $tag)
                            <x-chief::table.row>
                                <x-chief::table.data>
                                    <a
                                        href="{{ route('chief.tags.edit', $tag->getTagId()) }}"
                                        title="{{ $tag->getLabel() }}"
                                        class="inline-flex items-center gap-x-3"
                                    >
                                        <svg class="w-2.5 h-2.5" style="fill: {{ $tag->getColor() }};" viewBox="0 0 6 6" aria-hidden="true">
                                            <circle cx="3" cy="3" r="3" />
                                        </svg>

                                        <span class="font-medium body-dark hover:underline">{{ $tag->getLabel() }}</span>
                                    </a>
                                </x-chief::table.data>

                                <x-chief::table.data>
                                    @if($tag->getUsages() > 0)
                                        <p>In gebruik op {{ $tag->getUsages() }} pagina's</p>
                                    @else
                                        <p>Niet in gebruik</p>
                                    @endif
                                </x-chief::table.data>

                                <x-chief::table.data>
                                    <div class="flex justify-end -mr-2">
                                        <a href="{{ route('chief.tags.edit', $tag->getTagId()) }}">
                                            <x-chief::icon-button icon="icon-edit" color="grey" class="bg-white shadow-none text-grey-500" />
                                        </a>

                                        @include('chief-tags::tags.delete')
                                    </div>
                                </x-chief::table.data>
                            </x-chief::table.row>
                        @endforeach
                    </x-slot>
                </x-chief::table>
            </x-chief::window>
        @endforeach

        @if (count($tags) > 0)
            <div class="text-center">
                <a
                    href="{{ route('chief.taggroups.create') }}"
                    title="Tag groep toevoegen"
                    class="inline-flex items-center gap-0.5 mt-1 text-sm font-medium text-grey-500 hover:text-primary-500 justify-center"
                >
                    <svg width="18" height="18"> <use xlink:href="#icon-plus"></use> </svg>
                    <span>Groep toevoegen</span>
                </a>
            </div>
        @else
            <div class="py-4 space-y-4">
                <h2 class="h6 h6-dark">Nog geen enkele tag te zien... 😭</h2>
                <p class="body body-dark">
                    <a href="{{ route('chief.tags.create') }}" title="Tag toevoegen" class="btn btn-primary">
                        <x-chief::icon-label icon="icon-plus">
                            Jouw eerste tag toevoegen
                        </x-chief::icon-label>
                    </a>
                </p>
                <p class="text-sm body text-grey-500">
                    Tags houden jouw paginabeheer overzichtelijk.<br>
                    Gebruik ze om jouw werk te stroomlijnen en het paginaoverzicht te filteren.
                </p>

            </div>
        @endif
    </x-chief::page.grid>
</x-chief::page.template>
