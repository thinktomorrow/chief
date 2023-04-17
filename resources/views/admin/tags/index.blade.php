<x-chief::page.template title="Tags">
    <x-slot name="hero">
        <x-chief::page.hero title="Tags" class="max-w-3xl">
            <a href="{{ route('chief.taggroups.create') }}" title="Tag categorie toevoegen" class="btn btn-primary">
                Categorie toevoegen
            </a>
            <a href="{{ route('chief.tags.create') }}" title="Tag toevoegen" class="btn btn-primary">
                Tag toevoegen
            </a>
        </x-chief::page.hero>
    </x-slot>

    <x-chief::page.grid class="max-w-3xl">
        <div class="card">
            <div class="-my-4 divide-y divide-grey-100">
                @foreach($tags as $tagGroupId => $tagsByGroup)

                    @if(!$tagGroupId)
                        <h2>Algemeen</h2>
                    @elseif($tagGroup = $tagGroups->first(fn($tagGroup) => $tagGroup->getTagGroupId() == $tagGroupId))
                        <h2>{{ $tagGroup->getLabel() }} <a href="{{ route('chief.taggroups.edit', $tagGroup->getTagGroupId()) }}">edit</a> </h2>
                    @endif

                    <div class="flex justify-start gap-4">
                        @foreach($tagsByGroup as $tag)
                            <a
                                href="{{ route('chief.tags.edit', $tag->getTagId()) }}"
                                title="Aanpassen"
                                class="label text-white"
                                style="background-color:{{ $tag->getColor() }}"
                            >
                                {{ $tag->getLabel() }}
                            </a>
                        @endforeach
                    </div>

                @endforeach
            </div>
        </div>
    </x-chief::page.grid>
</x-chief::page.template>
