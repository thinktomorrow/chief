@extends('chief::layout.master')

@section('page-title', 'Nieuwe gebruiker uitnodigen')

@section('header')
    <div class="container-sm">
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
    <div class="container-sm">
        <div class="row">
            <div class="w-full">
                <div class="window window-white">
                    <form id="createForm" action="{{ route('chief.back.users.store') }}" method="POST" class="mb-0">
                        @csrf

                        <div class="space-y-8">
                            @include('chief::admin.users._form')
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
