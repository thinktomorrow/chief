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
            @foreach($timeTables as $timeTableModel)
                <div class="w-full">
                    <div class="space-y-4 card">
                        <a
                            href="{{ route('chief.timetables.edit', $timeTableModel->id) }}"
                            title="Aanpassen"
                            class="flex items-start justify-between gap-4 group"
                        >
                            <span class="font-medium leading-8 h1-dark body group-hover:underline">
                                {{ $timeTableModel->label }}
                            </span>

                            <x-chief::icon-button color="grey" class="shadow-none text-grey-500"/>
                        </a>

                        <x-chief-timetable::time-table
                            :time-table="$timeTableModel->timeTable"
                            :days="$timeTableModel->timeTable->forWeeks(4)"
                        />
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-chief::page.template>
