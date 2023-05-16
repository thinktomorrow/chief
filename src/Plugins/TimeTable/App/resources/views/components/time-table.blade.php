@props([
    'timeTable',
    'days' => $timeTable->forCurrentWeek(),
    'dayModels' => collect(), // Pass this to allow to edit the days, only in effect when isCalendar is set to false
    'weekDays' => ['Ma', 'Di', 'Wo', 'Do', 'Vr', 'Za', 'Zo'],
    'isCalendar' => true,
])

@php
    $weeks = collect($days)->chunk(7);
@endphp

<div class="sm:overflow-x-auto sm:border sm:rounded-md border-grey-100">
    @if($isCalendar)
        <div class="flex max-sm:hidden">
            @foreach($weekDays as $weekDay)
                <div @class([
                    'text-center text-sm h1-dark font-medium p-1 w-32 grow shrink-0 border-b border-grey-100',
                    'border-r' => !$loop->last,
                ])>
                    <span>{{ $weekDay }}</span>
                </div>
            @endforeach
        </div>
    @endif

    @foreach($weeks as $week)
        <div @class([
            'flex max-sm:flex-col max-sm:gap-3',
            'max-sm:hidden' => !$loop->first
        ])>
            @foreach($week as $date => $day)
                @include('chief-timetable::components.time-table-item', ['loop' => $loop])
            @endforeach
        </div>
    @endforeach
</div>
