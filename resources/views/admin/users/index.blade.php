<x-chief::template title="Admins">
    <x-slot name="hero">
        <x-chief::template.hero title="Admins" class="max-w-3xl">
            <a href="{{ route('chief.back.users.create') }}" title="Admin toevoegen" class="btn btn-primary">
                Admin toevoegen
            </a>
        </x-chief::template.hero>
    </x-slot>

    <x-chief::template.grid class="max-w-3xl">
        <div class="card">
            <div class="-my-4 divide-y divide-grey-100">
                @foreach($users as $user)
                    <div class="flex justify-between py-4">
                        <div class="space-y-1">
                            <div class="space-x-1">
                                @if(!chiefAdmin()->can('update-user') || ($user->hasRole('developer') && !chiefAdmin()->hasRole('developer')))
                                    <span class="font-medium body-dark">
                                        {{ ucfirst($user->firstname) }} {{ ucfirst($user->lastname) }}
                                    </span>
                                @else
                                    <a
                                        href="{{ route('chief.back.users.edit', $user->id) }}"
                                        title="Aanpassen"
                                        class="font-medium body-dark"
                                    >
                                        {{ ucfirst($user->firstname) }} {{ ucfirst($user->lastname) }}
                                    </a>
                                @endif

                                {!! $user->present()->enabledAsLabel() !!}
                            </div>

                            <div class="text-grey-400">
                                {{ ucfirst(implode(', ', $user->roleNames())) }}
                            </div>
                        </div>

                        @if(chiefAdmin()->can('update-user') && (!$user->hasRole('developer') || chiefAdmin()->hasRole('developer')) )
                            <options-dropdown class="link link-primary">
                                <div v-cloak class="dropdown-content">
                                    <a class="dropdown-link" href="{{ route('chief.back.users.edit', $user->id) }}">Aanpassen</a>
                                </div>
                            </options-dropdown>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </x-chief::template.grid>
</x-chief::template>
