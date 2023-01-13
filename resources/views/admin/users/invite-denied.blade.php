@extends('chief::layout.solo')

@section('title', 'Uitnodiging is afgewezen')

@section('content')
    <div class="container">
        <div class="min-h-screen row-center-center">
            <div class="space-y-6 w-128">
                <h1 class="text-center h1 display-dark">Jouw uitnodiging is afgewezen</h1>

                <div class="card">
                    <div class="prose prose-spacing prose-dark">
                        <p>Ok. Je hebt je uitnodiging tot {{ chiefSetting('app_name') }} afgewezen.</p>
                        <p>Mocht je toch opnieuw toegang wensen, kan je je wenden tot de beheerder ({{ chiefSetting('contact_name') }}).</p>
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
                </div>
            </div>
        </div>
    </div>
@endsection
