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
                <div>
                    <a @click="showModal('create-module')" class="btn btn-primary squished">
                        <i class="icon icon-zap icon-fw"></i> Voeg jouw eerste module toe.
                    </a>
                    <p class="stack">
                        <strong>Wat zijn modules nu eigenlijk?</strong><br>
                        Een module is een blokelement dat je kan toevoegen aan een of meerdere pagina's. <br>Bijvoorbeeld een blokje 'contacteer ons' of 'nieuwsbrief inschrijving'.
                        <br><br>
                        Gewoon even proberen, je doet niets fout :)
                    </p>
                </div>

            </div>
        @endif

        @if(!$modules->isEmpty())
            @foreach($modules as $module)
                @include('chief::back.modules._partials._rowitem')
                @include('chief::back.modules._partials.delete-modal')
            @endforeach
        @endif

    @include('chief::back.modules._partials.create-modal')
@stop
