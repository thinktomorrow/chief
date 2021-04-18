@extends('chief::back._layouts.master')

@section('page-title', 'Rollen')

@section('header')
    <div class="container-sm">
        @component('chief::back._layouts._partials.header')
            @slot('title', 'Rollen')

            @slot('breadcrumbs')
                <a href="{{ route('chief.back.dashboard') }}" class="link link-primary">
                    <x-icon-label type="back">Dashboard</x-icon-label>
                </a>
            @endslot

            <a href="{{ route('chief.back.roles.create') }}" class="btn btn-primary">Nieuwe rol toevoegen</a>
        @endcomponent
    </div>
@endsection

@section('content')
    <div class="container-sm">
        <div class="row">
            <div class="w-full">
                <div class="window window-white">
                    <div class="divide-y divide-grey-100 -m-12">
                        @foreach($roles as $role)
                            <a href="{{ route('chief.back.roles.edit', $role->id) }}" class="block px-6 py-4">
                                {{ $role->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
