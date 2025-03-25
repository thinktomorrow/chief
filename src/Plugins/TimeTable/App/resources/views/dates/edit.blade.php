@php
    $breadcrumb = new \Thinktomorrow\Chief\Admin\Nav\BreadCrumb('Terug naar schema', route('chief.timetables.edit', $timetable_id));
@endphp

<x-chief::page.template title="Uitzondering aanpassen">
    <x-slot name="header">
        <x-chief::page.header
            :breadcrumbs="[
                ['label' => 'Dashboard', 'url' => route('chief.back.dashboard'), 'icon' => 'home'],
                ['label' => 'Weekschema', 'url' => route('chief.timetables.edit', $timetable_id), 'icon' => 'calendar'],
                'Uitzondering aanpassen',
            ]"
        />
    </x-slot>

    <x-chief::window>
        <form id="tagsEditForm" action="{{ route('chief.timetable_dates.update', $model->id) }}" method="POST">
            @csrf
            @method('PUT')

            @foreach ($fields as $field)
                {!! $field->render() !!}
            @endforeach

            <x-chief::button data-slot="form-group" type="submit" variant="blue">Bewaar aanpassingen</x-chief::button>
        </form>
    </x-chief::window>
</x-chief::page.template>
