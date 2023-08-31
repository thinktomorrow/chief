<x-chief::page.template title="Admins">
    <x-slot name="hero">
        <x-chief::page.hero title="Admins" class="max-w-3xl">
            <a href="{{ route('chief.back.users.create') }}" title="Admin toevoegen" class="btn btn-primary">
                Admin toevoegen
            </a>
        </x-chief::page.hero>
    </x-slot>

    <x-chief::page.grid class="max-w-3xl">
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
                            <a href="{{ route('chief.back.users.edit', $user->id) }}" title="Pas user aan">
                                <x-chief::button>
                                    <svg><use xlink:href="#icon-edit"></use></svg>
                                </x-chief::button>
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </x-chief::page.grid>
</x-chief::page.template>
