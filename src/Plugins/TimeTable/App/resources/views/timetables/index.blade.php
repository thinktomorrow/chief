<x-chief::page.template title="Weekschema's">
    <x-slot name="hero">
        <x-chief::page.hero title="Weekschema's">
            <a href="{{ route('chief.timetables.create') }}" title="Weekschema toevoegen" class="btn btn-primary">
               Weekschema toevoegen
            </a>
        </x-chief::page.hero>
    </x-slot>

    <div class="container">
        <div class="row-start-start gutter-3">
            @foreach($timeTables as $timeTable)
                <div class="w-full">
                    <div class="card">
                        <a
                            href="{{ route('chief.timetables.edit', $timeTable->getId()) }}"
                            title="Aanpassen"
                            class="font-medium body-dark body"
                        >
                            {{ $timeTable->getLabel() }}
                        </a>
                        {{-- {{ dd($timeTable) }} --}}

                        <div class="flex flex-wrap items-start gap-3">
                            @foreach($timeTable->getDays() as $day)
                                <a href="{{ route('chief.timetable_days.edit', $day->id) }}" title="{{ $day->getLabel() }}">
                                    <x-chief-timetable::day :title="$day->getLabel()" :day="$day" class="w-48"/>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-chief::page.template>
