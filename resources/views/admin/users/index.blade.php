<x-chief::page.template title="Admins" container="md">
    <x-slot name="header">
        <x-chief::page.header>
            <x-slot name="actions">
                <x-chief::button href="{{ route('chief.back.users.create') }}" title="Voeg admin toe" variant="blue">
                    <x-chief::icon.plus-sign />
                    <span>Voeg admin toe</span>
                </x-chief::button>
            </x-slot>
        </x-chief::page.header>
    </x-slot>

    <x-chief::window>
        <div class="-my-4 divide-y divide-grey-100">
            @foreach ($users as $user)
                <div class="flex items-start justify-between py-4">
                    <div class="space-y-1">
                        <div class="space-x-1">
                            @if (! chiefAdmin()->can('update-user') || ($user->hasRole('developer') && ! chiefAdmin()->hasRole('developer')))
                                <span class="body-dark font-medium">
                                    {{ ucfirst($user->firstname) }} {{ ucfirst($user->lastname) }}
                                </span>
                            @else
                                <a
                                    href="{{ route('chief.back.users.edit', $user->id) }}"
                                    title="Aanpassen"
                                    class="body-dark font-medium"
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

                    @if (chiefAdmin()->can('update-user') && (! $user->hasRole('developer') || chiefAdmin()->hasRole('developer')))
                        <x-chief::button
                            href="{{ route('chief.back.users.edit', $user->id) }}"
                            title="Pas user aan"
                            variant="grey"
                            size="sm"
                        >
                            <x-chief::icon.quill-write />
                        </x-chief::button>
                    @endif
                </div>
            @endforeach
        </div>
    </x-chief::window>
</x-chief::page.template>
