@php
    $breadcrumb = new \Thinktomorrow\Chief\Admin\Nav\BreadCrumb('Terug naar overzicht', route('chief.timetables.index'));
@endphp

<x-chief::page.template title="Nieuw schema toevoegen">
    <x-slot name="header">
        <x-chief::page.header
            :breadcrumbs="[
                ['label' => 'Weekschema', 'url' => route('chief.timetables.index'), 'icon' => 'calendar'],
                'Nieuw schema toevoegen',
            ]"
        />
    </x-slot>

    <x-chief::window>
        <form id="timeTableCreateForm" action="{{ route('chief.timetables.store') }}" method="POST">
            @csrf

            @foreach ($fields as $field)
                {!! $field->render() !!}
            @endforeach

            <x-chief::button data-slot="form-group" type="submit" variant="blue">Maak weekschema aan</x-chief::button>
        </form>
    </x-chief::window>
</x-chief::page.template>
