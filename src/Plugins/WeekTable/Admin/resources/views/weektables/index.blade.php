<x-chief::page.template title="Tags">
    <x-slot name="hero">
        <x-chief::page.hero title="Weekschema's" class="max-w-3xl">
            <a href="{{ route('chief.weektables.create') }}" title="Weekschema toevoegen" class="btn btn-primary">
                Weekschema toevoegen
            </a>
        </x-chief::page.hero>
    </x-slot>

    <x-chief::page.grid class="max-w-3xl">
        <div class="card">
            <div class="-my-4 divide-y divide-grey-100">
                @foreach($weekTables as $weekTable)
                    <div>
                        <a
                            href="{{ route('chief.weektables.edit', $weekTable->getId()) }}"
                            title="Aanpassen"
                            class="label text-white"
                        >
                            {{ $weekTable->getLabel() }}
                        </a>
                    </div>

                @endforeach
            </div>
        </div>
    </x-chief::page.grid>
</x-chief::page.template>
