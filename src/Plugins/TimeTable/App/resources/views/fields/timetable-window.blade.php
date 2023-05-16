@php
    $timetable_id = $getActiveValue($locale ?? null);

    $timeTable = $timetable_id
        ? (new \Thinktomorrow\Chief\Plugins\TimeTable\App\TimeTableFactory())->create($timeTableModel = \Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models\TimeTableModel::find($timetable_id), app()->getLocale())
        : null;
@endphp

@if ($timeTable)
    <x-chief-timetable::time-table :time-table="$timeTable"/>

    <a href="{{ route('chief.timetables.edit', $timetable_id) }}" class="mt-4 text-sm link link-primary">
        Bewerk deze openingsuren
    </a>
@else
    <p class="body body-dark">
        Er is nog geen weekschema geselecteerd.
    </p>
@endif
