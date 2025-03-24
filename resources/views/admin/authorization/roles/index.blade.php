<x-chief::page.template title="Rechten" container="md">
    <x-slot name="header">
        <x-chief::page.header
            :breadcrumbs="[
                ['label' => 'Dashboard', 'url' => route('chief.back.dashboard'), 'icon' => 'home'],
                'Rechten'
            ]"
        >
            <x-slot name="actions">
                <x-chief::button variant="blue" href="{{ route('chief.back.roles.create') }}">
                    Rol toevoegen
                </x-chief::button>
            </x-slot>
        </x-chief::page.header>
    </x-slot>

    <x-chief::window class="card">
        <div class="-my-4 divide-y divide-grey-100">
            @foreach ($roles as $role)
                <a
                    href="{{ route('chief.back.roles.edit', $role->id) }}"
                    title="{{ $role->name }} aanpassen"
                    class="block py-4"
                >
                    <span class="body-dark font-medium hover:underline">
                        {{ ucfirst($role->name) }}
                    </span>
                </a>
            @endforeach
        </div>
    </x-chief::window>
</x-chief::page.template>
