@extends('chief::back._layouts.solo')

@section('title')
    Er ging iets fout
@endsection

@section('content')

    <div class="container min-h-screen flex items-center">
        <div class="row w-full justify-center my-32">
            <div class="xs-column-12 s-column-10 m-column-6 l-column-4">

                <h1 class="mb-8">Er ging iets fout. Het development team is op de hoogte gesteld en werkt hier zo snel mogelijk aan.</h2>

                <a class="btn btn-primary" href="{{ url('/admin') }}">Ga terug</a>

            </div>
        </div>
    </div>

@endsection