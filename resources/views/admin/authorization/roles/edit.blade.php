@extends('chief::layout.master')

@section('page-title', $role->name)

@section('header')
    <div class="container max-w-3xl">
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
    <div class="container max-w-3xl">
        <div class="row-start-start">
            <div class="w-full">
                <div class="card">
                    <form id="editForm" action="{{ route('chief.back.roles.update', $role->id) }}" method="POST">
                        @csrf
                        @method('put')

                        <div class="space-y-6">
                            @include('chief::admin.authorization.roles._form')
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
