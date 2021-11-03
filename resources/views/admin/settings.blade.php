@extends('chief::layout.master')

@section('page-title', 'settings')

@section('header')
    <div class="container-sm">
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
    <div class="container-sm">
        <div class="row">
            <div class="w-full">
                <x-chief::window>
                    <form action="{{ route('chief.back.settings.update') }}" id="updateForm" method="POST" role="form">
                        @csrf
                        @method('put')

                        <div class="-my-6 divide-y divide-grey-100">
                            @foreach($fields as $field)
                                <div class="py-6">
                                    <x-chief::field :field="$field" />
                                </div>
                            @endforeach
                        </div>
                    </form>
                </x-chief::window>
            </div>
        </div>
    </div>
@endsection
