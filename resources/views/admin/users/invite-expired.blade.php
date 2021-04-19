@extends('chief::back._layouts.solo')

@section('title', 'Uitnodiging niet langer geldig')

@section('content')
    <div class="container">
        <div class="row-center-center min-h-screen">
            <div class="w-full lg:w-1/2 2xl:w-1/3 window window-white space-y-6 prose prose-dark">
                <h1>Uitnodiging niet langer geldig.</h1>

                <div>
                    <p>Deze link is jammer genoeg ongeldig. Mogelijk is de reden een van de volgende:</p>

                    <ul>
                        <li>
                            De uitnodiging is reeds aanvaard.
                        </li>

                        <li>
                            De uitnodiging is vervallen.
                            Binnen 3 dagen van ontvangst dient een uitnodiging te worden aanvaard.
                        </li>

                        <li>
                            Zorg ervoor dat de volledige uitnodigingslink wordt gebruikt.
                            Van zodra een deeltje ontbreekt, is deze niet geldig.
                        </li>

                        <li>
                            De uitnodiging is teruggetrokken door de beheerder ({{ chiefSetting('contact_name') }}).
                        </li>
                    </ul>
                </div>

                <div class="space-x-4">
                    <a href="mailto:{{ chiefSetting('contact_email') }}" class="btn btn-primary">
                        Contacteer beheerder
                    </a>

                    <a href="{{ route('chief.back.login') }}" class="btn btn-secondary">
                        Ga naar login pagina
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
