@extends('chief::back._layouts.master')

@section('page-title', 'settings')

@section('header')
    <div class="container-sm">
        @component('chief::back._layouts._partials.header')
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
                    <form action="{{ route('chief.back.settings.update') }}" id="updateForm" method="POST" role="form">
                        {{ csrf_field() }}
                        <input name="_method" type="hidden" value="PUT">

                        <div class="space-y-12">
                            @foreach($fields as $field)
                                @formgroup
                                    @slot('label',$field->getLabel())
                                    @slot('description',$field->getDescription())
                                    @slot('isRequired', $field->required())
                                    {!! $field->render() !!}
                                @endformgroup
                            @endforeach

                            <button data-submit-form="updateForm" type="button" class="btn btn-primary">Wijzigingen opslaan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
