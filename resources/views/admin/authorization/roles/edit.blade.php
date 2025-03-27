<x-chief::page.template :title="ucfirst($role->name)" container="md">
    <x-slot name="header">
        <x-chief::page.header
            :breadcrumbs="[
                ['label' => 'Rechten', 'url' => route('chief.back.roles.index'), 'icon' => 'user-star'],
                ucfirst($role->name)
            ]"
        >
            <x-slot name="actions">
                <x-chief::button form="editForm" type="submit" variant="blue">Rol opslaan</x-chief::button>
            </x-slot>
        </x-chief::page.header>
    </x-slot>

    <x-chief::window>
        <form id="editForm" action="{{ route('chief.back.roles.update', $role->id) }}" method="POST">
            @csrf
            @method('put')

            @include('chief::admin.authorization.roles._form')
        </form>
    </x-chief::window>
</x-chief::page.template>
