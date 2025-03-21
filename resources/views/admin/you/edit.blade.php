<x-chief::page.template title="Bewerk jouw profiel" container="md">
    <x-chief::window title="Jouw gegevens" class="card">
        <form id="updateForm" action="{{ route('chief.back.you.update', $user->id) }}" method="POST">
            {!! csrf_field() !!}
            <input type="hidden" name="_method" value="PUT" />

            @include('chief::admin.you._form')
        </form>
    </x-chief::window>

    <x-chief::window title="Wachtwoord wijzigen" class="card">
        <div class="space-y-4">
            <p class="body text-grey-500">Om je wachtwoord te wijzigen, word je doorverwezen naar een aparte pagina.</p>

            <x-chief::button href="{{ route('chief.back.password.edit') }}" title="Wijzig wachtwoord" variant="grey">
                Wijzig wachtwoord
            </x-chief::button>
        </div>
    </x-chief::window>

    <div>
        <x-chief::button href="{{ route('chief.back.logout') }}" title="Uitloggen">
            <x-chief::icon.logout />
            <span>Uitloggen</span>
        </x-chief::button>
    </div>
</x-chief::page.template>
