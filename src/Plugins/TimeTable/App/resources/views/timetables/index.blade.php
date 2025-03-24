<x-chief::page.template title="Weekschema's">
    <x-slot name="header">
        <x-chief::page.header title="Weekschema's">
            <x-slot name="actions">
                <x-chief::button href="{{ route('chief.timetables.create') }}" variant="blue">
                    Voeg weekschema toe
                </x-chief::button>
            </x-slot>
        </x-chief::page.header>
    </x-slot>

    @foreach ($timeTables as $timeTableModel)
        <x-chief::window class="card">
            <div class="space-y-4">
                <a
                    href="{{ route('chief.timetables.edit', $timeTableModel->id) }}"
                    title="Aanpassen"
                    class="group flex items-start justify-between gap-4"
                >
                    <span class="h1-dark body font-medium leading-8 group-hover:underline">
                        {{ $timeTableModel->label }}
                    </span>

                    <x-chief::icon-button color="grey" class="text-grey-500 shadow-none" />
                </a>

                <x-chief-timetable::time-table
                    :time-table="$timeTableModel->timeTable"
                    :days="$timeTableModel->timeTable->forWeeks(4)"
                />
            </div>
        </x-chief::window>
    @endforeach
</x-chief::page.template>
