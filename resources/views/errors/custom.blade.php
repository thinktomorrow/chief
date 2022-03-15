@extends('chief::layout.solo')

@section('title')
    Er ging iets fout
@endsection

@section('content')
    <div class="container">
        <div class="min-h-screen row-center-center">
            <div class="space-y-6 w-128">
                <h1 class="text-center text-black">Er ging iets fout</h1>

                <div class="window">
                    <div class="prose prose-dark">
                        <p>
                            Onze developers werden op de hoogte gebracht en zullen uitzoeken wat er fout liep.<br>
                            Indien je dringend hulp nodig hebt bij dit probleem, kan je ons best contacteren.
                        </p>

                        <p class="space-x-2">
                            <a
                                href="{{ route('chief.back.dashboard') }}"
                                title="Naar het dashboard"
                                class="btn btn-primary"
                            > Naar het dashboard </a>

                            <a
                                href="mailto:chief@thinktomorrow.be"
                                title="Contacteer ons"
                                class="btn btn-primary-outline"
                            > Contacteer ons </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
