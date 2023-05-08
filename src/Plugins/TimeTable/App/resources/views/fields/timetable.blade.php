@php
    $timetable_id = $getActiveValue($locale ?? null);

    $timeTable = $timetable_id ? (new \Thinktomorrow\Chief\Plugins\TimeTable\App\TimeTableFactory())->create(
        $timeTableModel = \Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models\TimeTableModel::find($timetable_id),
        app()->getLocale(),
    ) : null;
@endphp

@if($timeTable)
<x-chief-timetable::time-table :time-table="$timeTable" :days="$timeTable->forWeeks(13)" :read="true"/>

<a href="{{ route('chief.timetables.edit', $timetable_id) }}">bewerk deze openingsuren</a>
@endif
