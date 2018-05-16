@extends('back._layouts.solo')

@section('page-title', 'Uitnodiging niet langer geldig')

@section('content')
    <div class="stack-l">

        <div class="stack">
            <h1>Uitnodiging niet langer geldig.</h1>

            <p>Deze link is jammer genoeg ongeldig. Mogelijk is de reden een van de volgende: </p>
            <ul>
                <li>de uitnodiging is vervallen. Binnen 3 dagen van ontvangst dient een uitnodiging te worden aanvaard.</li>
                <li>Zorg ervoor dat de volledige uitnodigingslink wordt gebruikt. Van zodra een deeltje ontbreekt, is deze niet geldig.</li>
                <li>de uitnodiging is teruggetrokken door de beheerder.</li>
            </ul>

            <div class="stack">
                <a class="btn btn-o-primary" href="mailto:{{ config('thinktomorrow.chief.contact.email') }}">Contacteer jouw beheerder</a>
                <a class="btn btn-link" href="{{ route('back.login') }}">Ga naar login pagina</a>
            </div>

        </div>

    </div>
@endsection