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

                            <x-chief::icon-button/>
                        </a>

                        <x-chief-timetable::time-table :time-table="$timeTableModel->timeTable" :days="$timeTableModel->timeTable->forWeeks(4)"/>

                        <div class="border rounded-md border-grey-100">
                            {{-- <div class="flex overflow-x-auto border-b border-grey-100">
                                @foreach(['Ma', 'Di', 'Wo', 'Do', 'Vr', 'Za', 'Zo'] as $weekDay)
                                    <div @class(['text-center text-sm h1-dark font-medium p-1 flex-1', 'border-r border-grey-100' => !$loop->last])>
                                        <span>{{ $weekDay }}</span>
                                    </div>
                                @endforeach
                            </div> --}}

                            {{-- <div class="flex border-b border-grey-100">
                                @foreach($timeTableModel->timeTable->forWeeks(2) as $date => $day)
                                    @php $date = \Illuminate\Support\Carbon::parse($date); @endphp

                                    <a
                                        href=""
                                        title="{{ \Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values\Day::fromDateTime($date)->getLabel() }}"
                                        @class([
                                            'flex-1 block p-2',
                                            'border-r border-grey-100' => !$loop->last,
                                        ])
                                    >
                                        <x-chief-timetable::day
                                            :date="$date"
                                            :slots="(iterator_to_array($day->getIterator()))"
                                            content="{{ $day->getData() }}"
                                            :exception="$timeTableModel->timeTable->isException($date)"
                                            :minimal="true"
                                        />
                                    </a>
                                @endforeach
                            </div> --}}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-chief::page.template>
