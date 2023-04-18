<x-chief::page.template title="Tags">
    <x-slot name="hero">
        <x-chief::page.hero title="Tags" class="max-w-3xl">
            <a href="{{ route('chief.tags.create') }}" title="Tag toevoegen" class="btn btn-primary">
                <x-chief::icon-label icon="icon-plus">
                    Tag toevoegen
                </x-chief::icon-label>
            </a>
        </x-chief::page.hero>
    </x-slot>

    <x-chief::page.grid class="max-w-3xl">
        <div class="card">
            <div class="-my-4 divide-y divide-grey-100">
                @foreach ($tags as $tagGroupId => $tagsByGroup)
                    @php
                        $tagGroup = $tagGroups->first(fn($tagGroup) => $tagGroup->getTagGroupId() == $tagGroupId);
                    @endphp

                    <div class="pt-4 pb-6 space-y-2">
                        <div class="flex justify-between gap-4">
                            @if (!$tagGroupId)
                                <div class="w-full mt-0.5 space-x-1">
                                    <p class="font-medium body-dark">
                                        Algemeen
                                    </p>
                                </div>
                            @elseif ($tagGroup)
                                <div class="w-full mt-0.5 space-x-1">
                                    <p class="font-medium body-dark">
                                        {{ $tagGroup->getLabel() }}
                                    </p>
                                </div>

                                <div class="shrink-0">
                                    <a href="{{ route('chief.taggroups.edit', $tagGroup->getTagGroupId()) }}" title="Aanpassen">
                                        <x-chief::icon-button icon="icon-edit" color="grey"/>
                                    </a>
                                </div>
                            @endif
                        </div>

                        <x-chief-tags::tags :tags="$tagsByGroup"/>
                    </div>
                @endforeach

                @php
                    $emptyTagGroups = $tagGroups->reject(fn($tagGroup) => $tags->keys()->contains($tagGroup->getTagGroupId()));
                @endphp

                @foreach ($emptyTagGroups as $tagGroupId => $tagsByGroup)
                    @php
                        $tagGroup = $tagGroups->first(fn($tagGroup) => $tagGroup->getTagGroupId() == $tagGroupId);
                    @endphp

                    <div class="pt-4 pb-6 space-y-2">
                        <div class="flex justify-between gap-4">
                            @if (!$tagGroupId)
                                <div class="w-full mt-0.5 space-x-1">
                                    <p class="font-medium body-dark">
                                        Algemeen
                                    </p>
                                </div>
                            @elseif ($tagGroup)
                                <div class="w-full mt-0.5 space-x-1">
                                    <p class="font-medium body-dark">
                                        {{ $tagGroup->getLabel() }}
                                    </p>
                                </div>

                                <div class="shrink-0">
                                    <a href="{{ route('chief.taggroups.edit', $tagGroup->getTagGroupId()) }}" title="Aanpassen">
                                        <x-chief::icon-button icon="icon-edit" color="grey"/>
                                    </a>
                                </div>
                            @endif
                        </div>

                        <p class="body text-grey-500">Deze categorie bevat nog geen tags.</p>
                    </div>
                @endforeach

                @if (count($tags) > 0)
                    <div class="py-4 space-y-2">
                        <a
                            href="{{ route('chief.taggroups.create') }}"
                            title="Tag categorie toevoegen"
                            class="justify-center w-full text-center btn btn-grey"
                        >
                            <x-chief::icon-label icon="icon-plus">Categorieën toevoegen</x-chief::icon-label>
                        </a>
                    </div>
                @else
                    <div class="py-4 space-y-2">
                        <p class="body text-grey-500">Er zijn momenteel nog geen tags.</p>
                    </div>
                @endif
            </div>
        </div>
    </x-chief::page.grid>
</x-chief::page.template>
