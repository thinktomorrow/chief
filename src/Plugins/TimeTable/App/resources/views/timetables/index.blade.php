<x-chief::page.template title="Weekschema's">
    <x-slot name="hero">
        <x-chief::page.hero title="Weekschema's" class="max-w-3xl">
            <a href="{{ route('chief.timetables.create') }}" title="Weekschema toevoegen" class="btn btn-primary">
               +
            </a>
        </x-chief::page.hero>
    </x-slot>

    <x-chief::page.grid class="max-w-3xl">
        <div class="card">
            <div class="-my-4 divide-y divide-grey-100">
                @foreach($timeTables as $timeTable)
                    <div>
                        <a
                            href="{{ route('chief.timetables.edit', $timeTable->getId()) }}"
                            title="Aanpassen"
                            class=""
                        >
                            {{ $timeTable->getLabel() }}
                        </a>
                    </div>

                @endforeach
            </div>
        </div>
    </x-chief::page.grid>
</x-chief::page.template>
