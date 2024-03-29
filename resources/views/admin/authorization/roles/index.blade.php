<x-chief::page.template title="Rollen">
    <x-slot name="hero">
        <x-chief::page.hero title="Rollen" class="max-w-3xl">
            <a href="{{ route('chief.back.roles.create') }}" title="Rol toevoegen" class="btn btn-primary">
                Rol toevoegen
            </a>
        </x-chief::page.hero>
    </x-slot>

    <x-chief::page.grid class="max-w-3xl">
        <div class="card">
            <div class="-my-4 divide-y divide-grey-100">
                @foreach($roles as $role)
                    <a
                        href="{{ route('chief.back.roles.edit', $role->id) }}"
                        title="{{ $role->name }} aanpassen"
                        class="block py-4"
                    >
                        <span class="font-medium body-dark hover:underline">
                            {{ ucfirst($role->name) }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </x-chief::page.grid>
</x-chief::page.template>
