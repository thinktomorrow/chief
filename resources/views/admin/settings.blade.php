@extends('chief::layout.master')

@section('page-title', 'settings')

@section('header')
    <div class="container max-w-3xl">
        @component('chief::layout._partials.header')
            @slot('title', 'Settings')

            @slot('breadcrumbs')
                <a href="{{ route('chief.back.dashboard') }}" class="link link-primary">
                    <x-chief-icon-label type="back">Dashboard</x-chief-icon-label>
                </a>
            @endslot

            <button form="updateForm" type="submit" class="btn btn-primary">Wijzigingen opslaan</button>
        @endcomponent
    </div>
@endsection

@section('content')
    <div class="container max-w-3xl">
        <div class="row-start-start">
            <div class="w-full">
                <div class="card">
                    <form action="{{ route('chief.back.settings.update') }}" id="updateForm" method="POST" role="form">
                        @csrf
                        @method('put')

                        <div class="space-y-6">
                            @foreach($fields as $field)
                                {!! $field->render() !!}
                            @endforeach
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
