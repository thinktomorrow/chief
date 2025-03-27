<x-chief::page.template title="Uitzondering toevoegen">
    <x-slot name="header">
        <x-chief::page.header
            :breadcrumbs="[
                ['label' => 'Weekschema', 'url' => route('chief.timetables.edit', $timetable_id), 'icon' => 'calendar'],
                'Uitzondering toevoegen',
            ]"
        />
    </x-slot>

    <x-chief::window>
        <form action="{{ route('chief.timetable_dates.store') }}" method="POST">
            @csrf

            @foreach ($fields as $field)
                {!! $field->render() !!}
            @endforeach

            <x-chief::button data-slot="form-group" type="submit" variant="blue">Voeg uitzondering toe</x-chief::button>
        </form>
    </x-chief::window>
</x-chief::page.template>
