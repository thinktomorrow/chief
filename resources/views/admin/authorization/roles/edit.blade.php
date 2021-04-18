@extends('chief::back._layouts.master')

@section('page-title', $role->name)

@section('header')
    <div class="container-sm">
        @component('chief::back._layouts._partials.header')
            @slot('title', $role->name)

            @slot('breadcrumbs')
                <a href="{{ route('chief.back.roles.index') }}" class="link link-primary">
                    <x-icon-label type="back">Terug naar rechten</x-icon-label>
                </a>
            @endslot

            <button data-submit-form="editForm" type="button" class="btn btn-primary">Opslaan</button>
        @endcomponent
    </div>
@endsection

@section('content')
    <div class="container-sm">
        <div class="row">
            <div class="w-full">
                <div class="window window-white">
                    <form
                        id="editForm"
                        action="{{ route('chief.back.roles.update', $role->id) }}"
                        method="POST"
                    >
                        {!! method_field('put') !!}
                        {!! csrf_field() !!}

                        <div class="space-y-12">
                            @include('chief::admin.authorization.roles._form')
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
