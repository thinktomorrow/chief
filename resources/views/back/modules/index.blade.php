@extends('chief::back._layouts.master')

@section('page-title', "Modules")

@component('chief::back._layouts._partials.header')
    @slot('title', 'Vaste modules')
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
            @foreach($modules as $key => $module_group)
                <h2 class="column-12">{{ucfirst($key)}}</h2>
                @foreach($module_group as $module)
                    @include('chief::back.managers._partials._rowitem', ['manager' => app(\Thinktomorrow\Chief\Management\Managers::class)->findByModel($module)])
                    @include('chief::back.managers._partials.delete-modal', ['manager' => app(\Thinktomorrow\Chief\Management\Managers::class)->findByModel($module)])
                @endforeach
            @endforeach
        @endif

    @include('chief::back.modules._partials.create-modal')
@stop
