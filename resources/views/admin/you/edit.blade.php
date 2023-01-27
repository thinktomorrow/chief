<x-chief::page.template title="Bewerk jouw profiel">
    <x-slot name="hero">
        <x-chief::page.hero title="Bewerk jouw profiel" class="max-w-3xl"/>
    </x-slot>

    <x-chief::page.grid class="max-w-3xl">
        <form id="updateForm" action="{{ route('chief.back.you.update',$user->id) }}" method="POST" class="card">
            {!! csrf_field() !!}
            <input type="hidden" name="_method" value="PUT">

            <div class="space-y-6">
                @include('chief::admin.you._form')

                <button form="updateForm" type="submit" class="btn btn-primary"> Opslaan </button>
            </div>
        </form>

        <x-chief-form::formgroup id="password" label="Wachtwoord" class="card">
            <x-slot name="description">
                Om je wachtwoord te wijzigen, word je doorverwezen naar een aparte pagina.
            </x-slot>

            <a
                href="{{ route('chief.back.password.edit') }}"
                title="Wijzig wachtwoord"
                class="btn btn-warning-outline"
            > Wijzig wachtwoord </a>
        </x-chief-form::formgroup>
    </x-chief::page.grid>
</x-chief::page.template>
