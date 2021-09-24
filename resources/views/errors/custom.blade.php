@extends('chief::layout.solo')

@section('title')
    Er ging iets fout
@endsection

@section('content')
        {{-- TODO: check text --}}
        <div class="min-h-screen row-center-center">
            <div class="max-w-lg space-y-6 window window-white window-md prose-dark">
                <h1>Er ging iets fout</h1>

                <p>
                    Onze developers werden op de hoogte gebracht en zullen uitzoeken wat er fout liep.<br>
                    Indien je dringend hulp nodig hebt bij dit probleem, kan je ons best contacteren.
                </p>

                <p class="space-x-4">
                    <a href="{{ route('chief.back.dashboard') }}" class="btn btn-primary">Naar het dashboard</a>
                    <a href="mailto:chief@thinktomorrow.be" class="btn btn-primary-outline">Contacteer ons</a>
                </p>
            </div>
        </div>
    </div>
@endsection
