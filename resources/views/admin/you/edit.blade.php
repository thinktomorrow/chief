@extends('chief::back._layouts.master')

@section('page-title', $user->fullname)

@section('header')
    <div class="container-sm">
        @component('chief::back._layouts._partials.header')
            @slot('title', 'Bewerk jouw profiel')

            @slot('breadcrumbs')
                <a href="{{ route('chief.back.dashboard') }}" class="link link-primary">
                    <x-icon-label type="back">Dashboard</x-icon-label>
                </a>
            @endslot

            <button data-submit-form="updateForm" type="button" class="btn btn-primary">Bewaren</button>
        @endcomponent
    </div>
@endsection

@section('content')
    <div class="container-sm">
        <div class="row">
            <div class="w-full">
                <div class="window window-white">
                    <form id="updateForm" action="{{ route('chief.back.you.update',$user->id) }}" method="POST">
                        {!! csrf_field() !!}
                        <input type="hidden" name="_method" value="PUT">

                        <div class="space-y-12">
                            @include('chief::admin.you._form')
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
