@php
    $breadcrumb = new \Thinktomorrow\Chief\Admin\Nav\BreadCrumb('Terug naar tags', route('chief.tags.index'));
@endphp

<x-chief::page.template title="Tag toevoegen" container="md">
    <x-slot name="header">
        <x-chief::page.header
            :breadcrumbs="[
                ['label' => 'Dashboard', 'url' => route('chief.back.dashboard'), 'icon' => 'home'],
                ['label' => 'Tags', 'url' => route('chief.tags.index'), 'icon' => 'tags'],
                'Tag toevoegen',
            ]"
        />
    </x-slot>

    <x-chief::window class="card">
        <form id="tagsCreateForm" action="{{ route('chief.tags.store') }}" method="POST">
            @csrf

            @foreach ($fields as $field)
                {!! $field->render() !!}
            @endforeach

            <x-chief::button data-slot="submit" type="submit" variant="blue">Maak tag aan</x-chief::button>
        </form>
    </x-chief::window>
</x-chief::page.template>
