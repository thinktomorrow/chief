@extends('chief::back._layouts.solo')

@section('title')
    Er ging iets fout
@endsection

@section('content')

    <div class="container min-h-screen flex items-center justify-center">
        <div class="row w-full justify-center">
            <div class="xs-column-12 s-column-10 m-column-6 l-column-4 z-20">

                @include('chief::back._layouts._partials.logo')

                <h1 class="mb-8">Er ging iets fout.</h1>
                <p class="mb-8">Het development team is op de hoogte gesteld en werkt hier zo snel mogelijk aan.</p>

                <a class="btn btn-primary mr-4" href="{{ url('/admin') }}">Naar het dashboard</a>
                <a href="mailto:chief@thinktomorrow.be">Contacteer ons</a>

            </div>
        </div>
    </div>

@endsection
