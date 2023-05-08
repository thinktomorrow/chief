@php
    $timetable_id = $getActiveValue($locale ?? null);

    $timeTable = (new \Thinktomorrow\Chief\Plugins\TimeTable\App\TimeTableFactory())->create(
        $timeTableModel = \Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models\TimeTableModel::find($timetable_id),
        app()->getLocale(),
    );
@endphp

<x-chief-timetable::time-table :time-table="$timeTable" :days="$timeTable->forWeeks(13)" :read="true"/>
{{--<x-chief-timetable::time-table :model="$timeTable" :days="$timeTable->getDays()" :read="true"/>--}}
