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
                <div class="window window-white window-md">
                    <form action="{{ route('chief.back.settings.update') }}" id="updateForm" method="POST" role="form" class="mb-0">
                        @csrf
                        @method('put')

                        <div class="space-y-8">
                            @foreach($fields->all() as $i => $fieldSet)
                                @include('chief::manager.fields.form.fieldset', ['index' => $i])
                            @endforeach

                            <div>
                                <div class="bg-white -window-x -window-y">
                                    <div class="window-x window-y">
                                        <button form="updateForm" type="submit" class="btn btn-primary">Wijzigingen opslaan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
