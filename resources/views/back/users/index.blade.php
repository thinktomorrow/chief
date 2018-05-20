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
                <a class="block" href="{{ route('back.users.edit', $user->id) }}">{{ $user->fullname }}</a>
            </div>

            <div style="margin-left:auto;">
                {!! $user->present()->enabledAsLabel() !!}
                {!! optional($user->invitation, function($invitation){ return $invitation->present()->stateAsLabel(); }) !!}

                <div class="inline-block">
                    @foreach($user->roleNames() as $roleName)
                        <span class="label label-o--primary">{{ $roleName }}</span>
                    @endforeach
                </div>

                <options-dropdown class="inline-block">
                    <div v-cloak>
                        <div>
                            <a class="block inset-s" href="{{ route('back.invites.resend', $user->id) }}">Stuur nieuwe uitnodiging</a>
                        </div>
                        <hr>
                        <div class="inset-s font-s">
                           <p>Om {{ $user->firstname }} tijdelijk de toegang <br>te ontnemen, kan je hem <a class="text-error">blokkeren</a>.</p>
                        </div>
                    </div>
                </options-dropdown>
            </div>
        </div>
    @endforeach
@endsection