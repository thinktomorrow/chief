@extends('chief::back._layouts.master')

@section('page-title', 'Gebruikers')

@chiefheader
    @slot('title', 'Gebruikers')
    <a href="{{ route('chief.back.users.create') }}" class="btn btn-link text-primary">Nodig een nieuwe gebruiker uit.</a>
@endchiefheader

@section('content')
    <div class="row gutter">
        @foreach($users as $user)
            <div class="s-column-6 m-column-4 inset-xs">
                <div class="row bg-white inset-s panel panel-default" style="height:100%">
                    <div>
                        @if(!chiefAdmin()->can('update-user') || ($user->hasRole('developer') && !chiefAdmin()->hasRole('developer')) )
                            <span>{{ $user->fullname }}</span>
                        @else
                            <a href="{{ route('chief.back.users.edit', $user->id) }}">{{ $user->fullname }}</a>
                        @endif
                        <div class="inline-block font-s">
                            {!! $user->present()->enabledAsLabel() !!}
                            {!! optional(optional($user->invitation)->present())->stateAsLabel() !!}
                        </div>

                        <div class="font-s text-subtle">
                            <?= implode(', ', $user->roleNames()) ?>
                        </div>

                    </div>

                    @if(chiefAdmin()->can('update-user') && (!$user->hasRole('developer') || chiefAdmin()->hasRole('developer')) )
                        <div style="margin-left:auto;">
                            <options-dropdown class="inline-block">
                                <div class="inset-s" v-cloak>
                                    <a class="block squished --link-with-bg" href="{{ route('chief.back.users.edit', $user->id) }}">Aanpassen</a>
                                </div>
                            </options-dropdown>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    <div class="stack inset-xs text-center">
        <a href="{{ route('chief.back.users.create') }}" class="btn btn-link text-primary">+ Nodig een nieuwe gebruiker uit.</a>
    </div>
@endsection
