@php
    $breadcrumb = new \Thinktomorrow\Chief\Admin\Nav\BreadCrumb('Terug naar schema', route('chief.timetables.edit', $timetable_id));
@endphp

<x-chief::page.template title="Uitzondering aanpassen">
    <x-slot name="hero">
        <x-chief::page.hero title="Uitzondering aanpassen" :breadcrumbs="[$breadcrumb]" class="max-w-3xl"></x-chief::page.hero>
    </x-slot>

    <x-chief::page.grid class="max-w-3xl">
        <form id="tagsEditForm" action="{{ route('chief.timetable_dates.update', $model->id) }}" method="POST" class="card">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                @foreach($fields as $field)
                    {!! $field->render() !!}
                @endforeach
            </div>

            <button class="btn btn-primary mt-4" type="submit">Bewaar aanpassingen</button>
        </form>
    </x-chief::page.grid>
</x-chief::page.template>
