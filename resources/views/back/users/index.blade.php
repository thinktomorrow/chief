@extends('back._layouts.master')

@section('page-title', 'Gebruikers')

@chiefheader
    @slot('title', 'Gebruikers')
    <a href="{{ route('back.users.create') }}" class="btn btn-link text-primary">Nodig een nieuwe gebruiker uit.</a>
@endchiefheader

@section('content')
    @foreach($users as $user)
        <div class="block stack panel panel-default inset-s center-y">
            <div>
                <a href="{{ route('back.users.edit', $user->id) }}">{{ $user->fullname }}</a>
                {!! $user->present()->enabledAsLabel() !!}
                {!! optional($user->invitation, function($invitation){ return $invitation->present()->stateAsLabel(); }) !!}
            </div>

            <div style="margin-left:auto;">
                <div class="inline-block">
                    @foreach($user->roleNames() as $roleName)
                        <span class="label label-o--primary">{{ $roleName }}</span>
                    @endforeach
                </div>
            </div>
        </div>
        <hr>
    @endforeach
@endsection