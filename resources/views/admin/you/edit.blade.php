@extends('chief::layout.master')

@section('page-title', $user->fullname)

@section('header')
    <div class="container-sm">
        @component('chief::layout._partials.header')
            @slot('title', 'Bewerk jouw profiel')

            @slot('breadcrumbs')
                <a href="{{ route('chief.back.dashboard') }}" class="link link-primary">
                    <x-icon-label type="back">Dashboard</x-icon-label>
                </a>
            @endslot
        @endcomponent
    </div>
@endsection

@section('content')
    <div class="container-sm">
        <div class="row gutter-3">
            <div class="w-full">
                <div class="window window-white">
                    <form id="updateForm" action="{{ route('chief.back.you.update',$user->id) }}" method="POST" class="mb-0">
                        {!! csrf_field() !!}
                        <input type="hidden" name="_method" value="PUT">

                        <div class="space-y-6">
                            @include('chief::admin.you._form')

                            <button form="updateForm" type="submit" class="btn btn-primary">Opslaan</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="w-full">
                <div class="window window-white">
                    <x-chief-formgroup label="Wachtwoord">
                        <x-slot name="description">
                            <p>Om je wachtwoord te wijzigen, word je doorverwezen naar een aparte pagina.</p>
                        </x-slot>

                        <a class="btn btn-warning-outline" href="{{ route('chief.back.password.edit') }}">Wijzig wachtwoord</a>
                    </x-chief-formgroup>
                </div>
            </div>
        </div>
    </div>
@endsection
