<x-chief::page.template title="Bewerk jouw profiel">
    <x-slot name="hero">
        <x-chief::page.hero title="Bewerk jouw profiel" class="max-w-3xl"/>
    </x-slot>

    <x-chief::page.grid class="max-w-3xl">
        <x-chief::window title="Jouw gegevens" class="card">
            <form id="updateForm" action="{{ route('chief.back.you.update',$user->id) }}" method="POST">
                {!! csrf_field() !!}
                <input type="hidden" name="_method" value="PUT">

                @include('chief::admin.you._form')
            </form>
        </x-chief::window>

        <x-chief::window title="Wachtwoord wijzigen" class="card">
            <div class="space-y-4">
                <p class="body text-grey-500">
                    Om je wachtwoord te wijzigen, word je doorverwezen naar een aparte pagina.
                </p>

                <div>
                    <a
                        href="{{ route('chief.back.password.edit') }}"
                        title="Wijzig wachtwoord"
                        class="btn btn-warning-outline"
                    > Wijzig wachtwoord </a>
                </div>
            </div>
        </x-chief::window>
    </x-chief::page.grid>
</x-chief::page.template>
