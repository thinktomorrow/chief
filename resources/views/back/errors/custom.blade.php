@extends('chief::back._layouts.solo')

@section('content')
    <h2>Er ging iets fout. Het development team is op de hoogte gesteld en werkt hier zo snel mogelijk aan.</h2>
    <a href="{{ url('/admin') }}">
        <button>Ga terug</button>
    </a>
@endsection