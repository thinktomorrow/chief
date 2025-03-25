<x-chief::page.template title="Groep toevoegen" container="md">
    <x-slot name="header">
        <x-chief::page.header
            :breadcrumbs="[
                ['label' => 'Dashboard', 'url' => route('chief.back.dashboard'), 'icon' => 'home'],
                ['label' => 'Tags', 'url' => route('chief.tags.index'), 'icon' => 'tags'],
                'Groep toevoegen',
            ]"
        />
    </x-slot>

    <x-chief::window>
        <form id="tagGroupsCreateForm" action="{{ route('chief.taggroups.store') }}" method="POST">
            @csrf

            @foreach ($fields as $field)
                {!! $field->render() !!}
            @endforeach

            <x-chief::button data-slot="form-group" type="submit" variant="blue">Voeg groep toe</x-chief::button>
        </form>
    </x-chief::window>
</x-chief::page.template>
