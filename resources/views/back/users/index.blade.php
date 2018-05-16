@extends('back._layouts.master')

@section('page-title', 'Gebruikers')

@chiefheader
    @slot('title', 'Gebruikers')
    <a href="{{ route('back.users.create') }}" class="btn btn-link text-primary">Nodig een nieuwe gebruiker uit.</a>
@endchiefheader

@section('content')
    @foreach($users as $user)
        <div class="block stack panel panel-default inset-s">
            <span class="indicator"></span>
            <a href="{{ route('back.users.edit', $user->id) }}">{{ $user->fullname }}</a>
            {!! $user->invitationLabel() !!}
        </div>
    @endforeach
@endsection