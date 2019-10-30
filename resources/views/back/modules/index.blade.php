@extends('chief::back._layouts.master')

@section('page-title', "Modules")

@component('chief::back._layouts._partials.header')
    @slot('title', 'Vaste modules')
        @if(! \Thinktomorrow\Chief\Modules\Module::anyAvailableForCreation())
            <div class="inline-group-s">
                <a @click="showModal('create-module')" class="btn btn-secondary inline-flex items-center">
                    <span class="mr-2"><svg width="18" height="18"><use xlink:href="#add"/></svg></span>
                    <span>Voeg een module toe</span>
                </a>
            </div>
        @endif
    @endcomponent

    @section('content')

        @if($modules->isEmpty())
            <div class="stack-l">
                <div>
                    <a @click="showModal('create-module')" class="btn btn-primary inline-flex items-center">
                        <span class="mr-2"><svg width="18" height="18"><use xlink:href="#zap"/></svg></span>
                        <span>Voeg jouw eerste module toe.</span>
                    </a>
                    <p class="stack">
                        <strong>Wat zijn modules nu eigenlijk?</strong><br>
                        Een module is een blokelement dat je kan toevoegen aan een of meerdere pagina's. <br>Bijvoorbeeld een blokje 'contacteer ons' of 'nieuwsbrief inschrijving'.
                    </p>
                </div>

            </div>
        @endif

        @if(!$modules->isEmpty())
            @foreach($modules as $groupLabel => $module_group)

                <div class="stack">
                    <h2>{{ ucfirst($groupLabel) }}</h2>
                    <div class="row gutter-s">
                        @foreach($module_group as $module)
                            @include('chief::back.managers._partials._rowitem', ['manager' => app(\Thinktomorrow\Chief\Management\Managers::class)->findByModel($module)])
                            @include('chief::back.managers._partials.delete-modal', ['manager' => app(\Thinktomorrow\Chief\Management\Managers::class)->findByModel($module)])
                        @endforeach
                    </div>
                </div>

            @endforeach
        @endif

    @include('chief::back.modules._partials.create-modal')
@stop
