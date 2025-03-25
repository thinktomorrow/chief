<x-chief::page.template title="Nieuwe rol toevoegen" container="md">
    <x-slot name="header">
        <x-chief::page.header
            :breadcrumbs="[
                ['label' => 'Dashboard', 'url' => route('chief.back.dashboard'), 'icon' => 'home'],
                ['label' => 'Rechten', 'url' => route('chief.back.roles.index'), 'icon' => 'user-star'],
                'Nieuwe rol toevoegen'
            ]"
        >
            <x-slot name="actions">
                <x-chief::button form="createForm" type="submit" variant="blue">Bewaar rol</x-chief::button>
            </x-slot>
        </x-chief::page.header>
    </x-slot>

    <x-chief::window>
        <form id="createForm" action="{{ route('chief.back.roles.store') }}" method="POST">
            @csrf

            @include('chief::admin.authorization.roles._form')
        </form>
    </x-chief::window>
</x-chief::page.template>
