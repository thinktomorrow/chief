@extends('chief::layout.master')

@section('page-title', 'Admins')

@section('header')
    <div class="container max-w-3xl">
        @component('chief::layout._partials.header')
            @slot('title', 'Admins')

            @slot('breadcrumbs')
                <a href="{{ route('chief.back.dashboard') }}" class="link link-primary">
                    <x-chief-icon-label type="back">Dashboard</x-chief-icon-label>
                </a>
            @endslot

            <a href="{{ route('chief.back.users.create') }}" class="btn btn-primary">Admin toevoegen</a>
        @endcomponent
    </div>
@endsection

@section('content')
    <div class="container max-w-3xl">
        <div class="row-start-start">
            <div class="w-full">
                <div class="card">
                    <div class="-my-4 divide-y divide-grey-100">
                        @foreach($users as $user)
                            <div class="flex justify-between py-4">
                                <div class="space-y-1">
                                    <div class="space-x-1">
                                        @if(!chiefAdmin()->can('update-user') || ($user->hasRole('developer') && !chiefAdmin()->hasRole('developer')) )
                                            <span class="display-base display-dark">
                                                {{ ucfirst($user->firstname) }} {{ ucfirst($user->lastname) }}
                                            </span>
                                        @else
                                            <a
                                                href="{{ route('chief.back.users.edit', $user->id) }}"
                                                title="Aanpassen"
                                                class="display-base display-dark"
                                            >
                                                {{ ucfirst($user->firstname) }} {{ ucfirst($user->lastname) }}
                                            </a>
                                        @endif

                                        {{-- TODO: does this work as expected? --}}
                                        {!! $user->present()->enabledAsLabel() !!}
                                    </div>

                                    <div class="text-grey-400">
                                        {{ ucfirst(implode(', ', $user->roleNames())) }}
                                    </div>
                                </div>

                                @if(chiefAdmin()->can('update-user') && (!$user->hasRole('developer') || chiefAdmin()->hasRole('developer')) )
                                    <options-dropdown class="link link-primary">
                                        <div v-cloak class="dropdown-content">
                                            <a class="dropdown-link" href="{{ route('chief.back.users.edit', $user->id) }}">Aanpassen</a>
                                        </div>
                                    </options-dropdown>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
