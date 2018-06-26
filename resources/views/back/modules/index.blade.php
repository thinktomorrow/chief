@extends('chief::back._layouts.master')

@section('page-title', "Modules")

@component('chief::back._layouts._partials.header')
    @slot('title', 'Modules')
        <div class="inline-group-s">
            <a @click="showModal('create-module')" class="btn btn-primary">
                <i class="icon icon-plus"></i>
                Voeg een module toe
            </a>
        </div>
    @endcomponent

    @section('content')

        @if($modules->isEmpty())
            <div class="center-center stack-xl">
                <a @click="showModal('create-module')" class="btn btn-primary squished-l">
                    <i class="icon icon-zap icon-fw"></i> Tijd om een eerste module toe te voegen
                </a>

                <p>
                    <strong>Wat zijn modules nu eigenlijk?</strong><br>
                    Een module is een blokelement dat je kan toevoegen aan een of meerdere pagina's.
                </p>
            </div>
        @endif

        @if(!$modules->isEmpty())
            @foreach($modules as $module)
                @include('chief::back.modules._partials._rowitem')
                @include('chief::back.modules._partials.delete-modal')
            @endforeach
            <div class="text-center">
                {!! $modules->render() !!}
            </div>
        @endif

    @include('chief::back.modules._partials.create-modal')
@stop
