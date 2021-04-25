@extends('chief::layout.solo')

@section('title', 'Uitnodiging niet langer geldig')

@section('content')
    <div class="container">
        <div class="row-center-center min-h-screen">
            <div class="w-full lg:w-1/2 2xl:w-1/3 window window-white space-y-6 prose prose-dark">
                <h1>Jouw uitnodiging is afgewezen.</h1>

                <div>
                    <p>Ok. Je hebt je uitnodiging tot {{ chiefSetting('app_name') }} afgewezen.</p>
                    <p>Mocht je toch opnieuw toegang wensen, kan je je wenden tot de beheerder ({{ chiefSetting('contact_name') }}).</p>
                </div>

                <div class="space-x-4">
                    <a href="mailto:{{ chiefSetting('contact_email') }}" class="btn btn-primary">
                        Contacteer beheerder
                    </a>

                    <a href="{{ route('chief.back.login') }}" class="btn btn-primary-outline">
                        Ga naar login pagina
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
