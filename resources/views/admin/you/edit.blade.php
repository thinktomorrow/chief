@extends('chief::layout.master')

@section('page-title', $user->fullname)

@section('header')
    <div class="container-sm">
        @component('chief::layout._partials.header')
            @slot('title', 'Bewerk jouw profiel')

            @slot('breadcrumbs')
                <a href="{{ route('chief.back.dashboard') }}" class="link link-primary">
                    <x-chief-icon-label type="back">Dashboard</x-chief-icon-label>
                </a>
            @endslot
        @endcomponent
    </div>
@endsection

@section('content')
    <div class="container-sm">
        <div class="row gutter-3">
            <div class="w-full">
                <x-chief-forms::window>
                    <form id="updateForm" action="{{ route('chief.back.you.update',$user->id) }}" method="POST">
                        {!! csrf_field() !!}
                        <input type="hidden" name="_method" value="PUT">

                        <div class="space-y-6">
                            @include('chief::admin.you._form')

                            <button form="updateForm" type="submit" class="btn btn-primary"> Opslaan </button>
                        </div>
                    </form>
                </x-chief-forms::window>
            </div>

            <div class="w-full">
                <x-chief-forms::window>
                    <x-chief::field.form label="Wachtwoord">
                        <x-slot name="description">
                            <p>Om je wachtwoord te wijzigen, word je doorverwezen naar een aparte pagina.</p>
                        </x-slot>

                        <a
                            class="btn btn-warning-outline"
                            title="Wijzig wachtwoord"
                            href="{{ route('chief.back.password.edit') }}"
                        > Wijzig wachtwoord </a>
                    </x-chief::field.form>
                </x-chief-forms::window>
            </div>
        </div>
    </div>
@endsection
