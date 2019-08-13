@extends('chief::back._layouts.solo')

@section('page-title', 'Uitnodiging niet langer geldig')

@section('content')
    <div class="container min-h-screen flex items-center">
        <div class="row w-full justify-center my-32">
            <div class="xs-column-12 s-column-10 m-column-6 l-column-4 relative z-20">

                <h1 class="mb-8">Uitnodiging niet langer geldig.</h1>

                <p class="mb-4">Deze link is jammer genoeg ongeldig. Mogelijk is de reden een van de volgende: </p>
                
                <ul class="list-disc ml-6">
                    <li>de uitnodiging is reeds aanvaard.</li>
                    <li>de uitnodiging is vervallen. Binnen 3 dagen van ontvangst dient een uitnodiging te worden aanvaard.</li>
                    <li>Zorg ervoor dat de volledige uitnodigingslink wordt gebruikt. Van zodra een deeltje ontbreekt, is deze niet geldig.</li>
                    <li>de uitnodiging is teruggetrokken door de beheerder.</li>
                </ul>

                <div class="stack">
                    <a class="btn btn-primary mr-4" href="mailto:{{ chiefSetting('contact_email') }}">Contacteer jouw beheerder ({{ chiefSetting('contact_name') }})</a>
                    <a href="{{ route('chief.back.login') }}">Ga naar login pagina</a>
                </div>

            </div>
        </div>
    </div>
@endsection