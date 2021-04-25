@extends('chief::layout.master')

@section('page-title', "Modules")

@component('chief::layout._partials.header')
    @slot('title', 'Gedeelde modules')
            <div class="inline-group-s">
                <a @click="showModal('create-module')" class="btn btn-primary-outline inline-flex items-center">
                    <span class="mr-2"><svg width="18" height="18"><use xlink:href="#add"/></svg></span>
                    <span>Voeg een module toe</span>
                </a>
            </div>
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
                    <h2>{{ $module_group['manager']->adminLabel('label') }}</h2>
                    <div class="row gutter-s">
                        @foreach($module_group['modules'] as $model)
                            @include('chief::manager._index._card', [
                                'manager' => $module_group['manager'],
                            ])
                        @endforeach
                    </div>
                </div>

            @endforeach
        @endif

    @include('chief::OLD.modules._partials.create-shared-modal')
@stop
