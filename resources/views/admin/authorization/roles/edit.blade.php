@extends('chief::layout.master')

@section('page-title', $role->name)

@section('header')
    <div class="container-sm">
        @component('chief::layout._partials.header')
            @slot('title', $role->name)

            @slot('breadcrumbs')
                <a href="{{ route('chief.back.roles.index') }}" class="link link-primary">
                    <x-chief-icon-label type="back">Terug naar rechten</x-chief-icon-label>
                </a>
            @endslot

            <button form="editForm" type="submit" class="btn btn-primary">Opslaan</button>
        @endcomponent
    </div>
@endsection

@section('content')
    <div class="container-sm">
        <div class="row">
            <div class="w-full">
                <x-chief-forms::window>
                    <form id="editForm" action="{{ route('chief.back.roles.update', $role->id) }}" method="POST">
                        @csrf
                        @method('put')

                        <div class="space-y-6">
                            @include('chief::admin.authorization.roles._form')
                        </div>
                    </form>
                </x-chief-forms::window>
            </div>
        </div>
    </div>
@endsection
