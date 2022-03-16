@extends('chief::layout.master')

@section('page-title', 'Rollen')

@section('header')
    <div class="container-sm">
        @component('chief::layout._partials.header')
            @slot('title', 'Rollen')

            @slot('breadcrumbs')
                <a href="{{ route('chief.back.dashboard') }}" class="link link-primary">
                    <x-chief-icon-label type="back">Dashboard</x-chief-icon-label>
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
                <div class="card">
                    <div class="-my-4 divide-y divide-grey-100">
                        @foreach($roles as $role)
                            <a
                                href="{{ route('chief.back.roles.edit', $role->id) }}"
                                title="Edit {{ $role->name }}"
                                class="block py-4 display-dark display-base"
                            > {{ ucfirst($role->name) }} </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
