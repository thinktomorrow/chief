@php
    $breadcrumb = new \Thinktomorrow\Chief\Admin\Nav\BreadCrumb('Terug', route('chief.timetables.edit', $timetable_id));
@endphp

<x-chief::page.template title="Uitzondering toevoegen">
    <x-slot name="hero">
        <x-chief::page.hero title="Nieuwe uitzondering toevoegen" :breadcrumbs="[$breadcrumb]" class="max-w-3xl"></x-chief::page.hero>
    </x-slot>

    <x-chief::page.grid class="max-w-3xl">
        <form action="{{ route('chief.timetable_dates.store') }}" method="POST" class="card">
            @csrf

            <div class="space-y-4">
                @foreach($fields as $field)
                    {!! $field->render() !!}
                @endforeach
            </div>

            <button class="btn btn-primary mt-4" type="submit">Voeg uitzondering toe</button>
        </form>
    </x-chief::page.grid>
</x-chief::page.template>
