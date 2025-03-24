@php
    $breadcrumb = new \Thinktomorrow\Chief\Admin\Nav\BreadCrumb('Terug', route('chief.timetables.edit', $model->timetable_id));
@endphp

<x-chief::page.template :title="$model->getLabel()">
    <x-slot name="header">
        <x-chief::page.header
            :breadcrumbs="[
                ['label' => 'Dashboard', 'url' => route('chief.back.dashboard'), 'icon' => 'home'],
                ['label' => 'Weekschema', 'url' => route('chief.timetables.edit', $model->timetable_id), 'icon' => 'calendar'],
                $model->getLabel(),
            ]"
        />
    </x-slot>

    <x-chief::window class="card">
        <form
            data-form
            id="timeTableEditForm"
            action="{{ route('chief.timetable_days.update', $model->id) }}"
            method="POST"
        >
            @csrf
            @method('PUT')

            @foreach ($fields as $field)
                {!! $field->render() !!}
            @endforeach

            <x-chief::button data-slot="submit" type="submit" variant="blue">Bewaar aanpassingen</x-chief::button>
        </form>
    </x-chief::window>
</x-chief::page.template>
