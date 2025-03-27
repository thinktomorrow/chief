@php
    $timetable_id = $getActiveValue($locale ?? null);

    $timeTable = $timetable_id ? (new \Thinktomorrow\Chief\Plugins\TimeTable\App\TimeTableFactory())->create($timeTableModel = \Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models\TimeTableModel::find($timetable_id), app()->getLocale()) : null;
@endphp

@if ($timeTable)
    <x-chief-timetable::time-table :time-table="$timeTable" />

    <x-chief::link href="{{ route('chief.timetables.edit', $timetable_id) }}" size="sm" variant="blue" class="mt-4">
        Bewerk deze openingsuren
    </x-chief::link>
@else
    <p class="body body-dark">Er is nog geen weekschema geselecteerd.</p>
@endif
