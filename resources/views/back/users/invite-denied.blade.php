@extends('chief::back._layouts.solo')

@section('page-title', 'Uitnodiging niet langer geldig')

@section('content')
    <div class="container min-h-screen flex items-center">
        <div class="row w-full justify-center my-32">
            <div class="xs-column-12 s-column-10 m-column-6 l-column-4 relative z-20">

                <h1 class="mb-8">Jouw uitnodiging is afgewezen.</h1>

                <p>Ok. Je hebt jouw uitnodiging tot {{ chiefSetting('app_name') }} afgewezen.</p>
                <p>Mocht je toch opnieuw toegang wensen, kan je je wenden tot de beheerder.</p>

                <div class="stack">
                    <a class="btn btn-primary mr-4" href="mailto:{{ chiefSetting('contact_email') }}">Contacteer jouw beheerder ({{ chiefSetting('contact_name') }})</a>
                    <a href="{{ route('chief.back.login') }}">Ga naar login pagina</a>
                </div>

            </div>
        </div>
    </div>
@endsection
