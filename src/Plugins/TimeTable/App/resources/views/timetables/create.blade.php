@php
    $breadcrumb = new \Thinktomorrow\Chief\Admin\Nav\BreadCrumb('Terug naar overzicht', route('chief.timetables.index'));
@endphp

<x-chief::page.template title="Schema toevoegen">
    <x-slot name="hero">
        <x-chief::page.hero title="Nieuw schema toevoegen" :breadcrumbs="[$breadcrumb]" class="max-w-3xl"></x-chief::page.hero>
    </x-slot>

    <x-chief::page.grid class="max-w-3xl">
        <form id="timeTableCreateForm" action="{{ route('chief.timetables.store') }}" method="POST" class="card">
            @csrf

            <div class="space-y-4">
                @foreach($fields as $field)
                    {!! $field->render() !!}
                @endforeach
            </div>

            <button class="btn btn-primary mt-4" type="submit">Maak weekschema aan</button>
        </form>
    </x-chief::page.grid>
</x-chief::page.template>
