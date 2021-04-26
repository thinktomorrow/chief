@extends('chief::layout.master')

@section('page-title', 'settings')

@section('header')
    <div class="container-sm">
        @component('chief::layout._partials.header')
            @slot('title', 'Settings')

            @slot('breadcrumbs')
                <a href="{{ route('chief.back.dashboard') }}" class="link link-primary">
                    <x-icon-label type="back">Dashboard</x-icon-label>
                </a>
            @endslot

            <button data-submit-form="updateForm" type="button" class="btn btn-primary">Wijzigingen opslaan</button>
        @endcomponent
    </div>
@endsection

@section('content')
    <div class="container-sm">
        <div class="row">
            <div class="w-full">
                <div class="window window-white">
                    <form action="{{ route('chief.back.settings.update') }}" id="updateForm" method="POST" role="form" class="mb-0">
                        @csrf
                        @method('put')

                        <div class="space-y-8">
                            @foreach($fields as $field)
                                <x-chief-formgroup label="{{ $field->getLabel() }}" isRequired="{{ $field->required() }}" name="{{ $field->getName($locale ?? null) }}">
                                    @if($field->getDescription())
                                        <x-slot name="{{ $field->getDescription() }}"></x-slot>
                                    @endif

                                    {!! $field->render() !!}
                                </x-chief-formgroup>
                            @endforeach

                            <button data-submit-form="updateForm" type="button" class="btn btn-primary">Wijzigingen opslaan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
