@extends('back._layouts.solo')

@section('page-title', 'Uitnodiging niet langer geldig')

@section('content')
    <div class="stack-l">

        <div class="stack">
            <h1>Jouw uitnodiging is afgewezen.</h1>

            <p>Ok. Je hebt jouw uitnodiging tot {{ config('thinktomorrow.chief.name') }} afgewezen. </p>
            <p>Mocht je toch opnieuw toegang wensen, kan je je wenden tot de beheerder. </p>

            <div class="stack">
                <a class="btn btn-o-primary" href="mailto:{{ config('thinktomorrow.chief.contact.email') }}">Contacteer jouw beheerder</a>
                <a class="btn btn-link" href="{{ route('back.login') }}">Ga naar login pagina</a>
            </div>

        </div>

    </div>
@endsection