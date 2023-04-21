<x-chief::page.template title="Tags">
    <x-slot name="hero">
        <x-chief::page.hero title="Tags" class="max-w-3xl">
{{--            <a href="{{ route('chief.tags.create') }}" title="Tag toevoegen" class="btn btn-primary">--}}
{{--                <x-chief::icon-label icon="icon-plus">--}}
{{--                    Tag toevoegen--}}
{{--                </x-chief::icon-label>--}}
{{--            </a>--}}
        </x-chief::page.hero>
    </x-slot>

    <x-chief::page.grid class="max-w-3xl">
        <div class="card">
            <div class="-my-4 divide-y divide-grey-100">
                @if($tagGroups->count() == 1)
                    <div class="pt-4 pb-6 space-y-2">
                        <div class="flex flex-wrap gap-2 item-start">
                            <x-chief-tags::tags :tags="$tags" :threshold="9999"/>
                        </div>
                        <p class="body body-dark">
                            <a href="{{ route('chief.tags.create') }}" title="Tag toevoegen" class="btn btn-primary">
                                <x-chief::icon-label icon="icon-plus">
                                    
                                </x-chief::icon-label>
                            </a>
                        </p>
                    </div>
                @else
                    @foreach($tagGroups as $tagGroup)
                        <div class="pt-4 pb-6 space-y-2">
                            <div class="flex justify-between gap-4">
                                <div class="w-full mt-0.5 space-x-1">
                                    <p class="font-medium body-dark">
                                        {{ $tagGroup->getLabel() }}
                                    </p>
                                </div>

                                @if($tagGroup->getTagGroupId())
                                    <div class="shrink-0">
                                        <a href="{{ route('chief.taggroups.edit', $tagGroup->getTagGroupId()) }}" title="Aanpassen">
                                            <x-chief::icon-button icon="icon-edit" color="grey"/>
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="flex flex-wrap gap-2 item-start">
                                @if(($tagsByGroup = $tags->filter(fn($tag) => $tag->getTagGroupId() == $tagGroup->getTagGroupId())) && !$tagsByGroup->isEmpty())
                                    <x-chief-tags::tags :tags="$tagsByGroup" :threshold="9999"/>
                                @else
                                    <p class="body text-grey-500">Deze categorie bevat nog geen tags.</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif


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
                    <div class="py-4 space-y-4">
                        <h2 class="h6 h6-dark">Nog geen enkele tag te zien... 😭</h2>
                        <p class="body body-dark">
                            <a href="{{ route('chief.tags.create') }}" title="Tag toevoegen" class="btn btn-primary">
                                <x-chief::icon-label icon="icon-plus">
                                    Jouw eerste tag toevoegen
                                </x-chief::icon-label>
                            </a>
                        </p>
                        <p class="body text-grey-500 text-sm">
                            Tags houden jouw paginabeheer overzichtelijk.<br>
                            Gebruik ze om jouw werk te stroomlijnen en het paginaoverzicht te filteren.
                        </p>

                    </div>
                @endif
            </div>
        </div>
    </x-chief::page.grid>
</x-chief::page.template>
