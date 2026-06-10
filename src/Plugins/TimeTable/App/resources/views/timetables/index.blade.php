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
        <x-chief::window>
            <div class="space-y-4">
                <div class="flex items-start justify-between gap-4">
                    <a
                        href="{{ route('chief.timetables.edit', $timeTableModel->id) }}"
                        title="Aanpassen"
                        class="group min-w-0"
                    >
                        <span class="h1-dark body leading-8 font-medium group-hover:underline">
                            {{ $timeTableModel->label }}
                        </span>
                    </a>

                    <x-chief::button
                        href="{{ route('chief.timetables.edit', $timeTableModel->id) }}"
                        title="Aanpassen"
                        variant="grey"
                        size="sm"
                        class="shrink-0"
                    >
                        <x-chief::icon.quill-write />
                    </x-chief::button>
                </div>

                <x-chief-timetable::time-table
                    :time-table="$timeTableModel->timeTable"
                    :days="$timeTableModel->timeTable->forWeeks(4)"
                />
            </div>
        </x-chief::window>
    @endforeach
</x-chief::page.template>
