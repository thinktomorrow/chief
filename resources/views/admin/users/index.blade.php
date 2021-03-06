@extends('chief::layout.master')

@section('page-title', 'Admins')

@section('header')
    <div class="container-sm">
        @component('chief::layout._partials.header')
            @slot('title', 'Admins')

            @slot('breadcrumbs')
                <a href="{{ route('chief.back.dashboard') }}" class="link link-primary">
                    <x-icon-label type="back">Dashboard</x-icon-label>
                </a>
            @endslot

            <a href="{{ route('chief.back.users.create') }}" class="btn btn-primary">Admin toevoegen</a>
        @endcomponent
    </div>
@endsection

@section('content')
    <div class="container-sm">
        <div class="row">
            <div class="w-full">
                <div class="window window-white">
                    <div class="-m-8 divide-y divide-grey-100">
                        @foreach($users as $user)
                            <div class="flex items-center justify-between px-6 py-4">
                                <div class="space-y-1">
                                    <div class="space-x-2">
                                        @if(!chiefAdmin()->can('update-user') || ($user->hasRole('developer') && !chiefAdmin()->hasRole('developer')) )
                                            <span class="text-lg font-medium text-grey-900">{{ $user->fullname }}</span>
                                        @else
                                            <a class="text-lg font-medium text-grey-900" href="{{ route('chief.back.users.edit', $user->id) }}">
                                                {{ ucfirst($user->firstname) }} {{ ucfirst($user->lastname) }}
                                            </a>
                                        @endif

                                        {{-- TODO: does this work as expected? --}}
                                        <span class="text-sm">
                                            {!! $user->present()->enabledAsLabel() !!}
                                        </span>
                                    </div>

                                    <div class="text-grey-500">
                                        {{ implode(', ', $user->roleNames()) }}
                                    </div>
                                </div>

                                @if(chiefAdmin()->can('update-user') && (!$user->hasRole('developer') || chiefAdmin()->hasRole('developer')) )
                                    <options-dropdown>
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
