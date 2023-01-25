@extends('chief::layout.master')

@section('page-title', 'Nieuwe gebruiker uitnodigen')

@section('header')
    <div class="container max-w-3xl">
        @component('chief::layout._partials.header')
            @slot('title', 'Nieuwe gebruiker')

            @slot('breadcrumbs')
                <a href="{{ route('chief.back.users.index') }}" class="link link-primary">
                    <x-chief-icon-label type="back">Terug naar admins</x-chief-icon-label>
                </a>
            @endslot

            <button form="createForm" type="submit" class="btn btn-primary">Uitnodiging versturen</button>
        @endcomponent
    </div>
@endsection

@section('content')
    <div class="container max-w-3xl">
        <div class="row-start-start">
            <div class="w-full">
                <div class="card">
                    <form id="createForm" action="{{ route('chief.back.users.store') }}" method="POST">
                        @csrf

                        <div class="space-y-6">
                            @include('chief::admin.users._form')
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
