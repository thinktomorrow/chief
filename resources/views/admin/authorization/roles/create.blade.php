@extends('chief::layout.master')

@section('page-title', 'Nieuwe rol toevoegen')

@section('header')
    <div class="container-sm">
        @component('chief::layout._partials.header')
            @slot('title', 'Nieuwe rol toevoegen')

            @slot('breadcrumbs')
                <a href="{{ route('chief.back.roles.index') }}" class="link link-primary">
                    <x-icon-label type="back">Terug naar rechten</x-icon-label>
                </a>
            @endslot

            <button data-submit-form="createForm" type="button" class="btn btn-primary">Voeg nieuwe rol toe</button>
        @endcomponent
    </div>
@endsection

@section('content')
    <div class="container-sm">
        <div class="row">
            <div class="w-full">
                <div class="window window-white">
                    <form
                        id="createForm"
                        action="{{ route('chief.back.roles.store') }}"
                        method="POST"
                        class="mb-0"
                    >
                        @csrf

                        <div class="space-y-8">
                            @include('chief::admin.authorization.roles._form')
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
