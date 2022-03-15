@extends('chief::layout.solo')

@section('title', 'Uitnodiging niet langer geldig')

@section('content')
    <div class="container">
        <div class="min-h-screen row-center-center">
            <div class="space-y-6 w-128">
                <h1 class="text-center text-black">Uitnodiging niet langer geldig</h1>

                <x-chief-forms::window>
                    <div class="prose prose-dark">
                        <p>Deze link is jammer genoeg ongeldig. Mogelijk is de reden een van de volgende:</p>

                        <ul>
                            <li> De uitnodiging is reeds aanvaard. </li>
                            <li> De uitnodiging is vervallen. Binnen 3 dagen van ontvangst dient een uitnodiging te worden aanvaard. </li>
                            <li> Zorg ervoor dat de volledige uitnodigingslink wordt gebruikt. Van zodra een deeltje ontbreekt, is deze niet geldig. </li>
                            <li> De uitnodiging is teruggetrokken door de beheerder ({{ chiefSetting('contact_name') }}). </li>
                        </ul>
                    </div>

                    <div class="space-x-2">
                        <a
                            href="mailto:{{ chiefSetting('contact_email') }}"
                            title="Contacteer beheerder"
                            class="btn btn-primary"
                        > Contacteer beheerder </a>

                        <a
                            href="{{ route('chief.back.login') }}"
                            title="Ga naar login pagina"
                            class="btn btn-primary-outline"
                        > Ga naar login pagina </a>
                    </div>
                </x-chief-forms::window>
            </div>
        </div>
    </div>
@endsection
