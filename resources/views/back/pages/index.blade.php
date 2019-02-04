@extends('chief::back._layouts.master')

@section('page-title', "Pagina's")

@component('chief::back._layouts._partials.header')
    @slot('title', $collectionDetails->plural)
        <div class="inline-group-s">
            <a @click="showModal('create-page')" class="btn btn-primary row center-y">
                <i class="icon icon-plus"></i>
                Voeg een {{ $collectionDetails->singular }} toe
            </a>
        </div>
    @endcomponent

    @section('content')

        @if($drafts->isEmpty() && $published->isEmpty())
            <div class="center-center stack-xl">
                <a @click="showModal('create-page')" class="btn btn-primary squished-l">
                    <i class="icon icon-zap icon-fw"></i> Tijd om een {{ $collectionDetails->singular }} toe te voegen
                </a>
            </div>
        @endif

        @if(!$drafts->isEmpty() || !$published->isEmpty() || !$archived->isEmpty())
        <tabs v-cloak>
            @if( $drafts->total() > 0)
                <tab name="Drafts ({{ $drafts->total() }})" id="drafts">
                        @foreach($drafts as $page)
                            @include('chief::back.pages._partials._rowitem')
                            @include('chief::back.pages._partials.delete-modal')
                        @endforeach
                        <div class="text-center">
                            {!! $drafts->render() !!}
                        </div>
                </tab>
            @endif

            @if( $published->total() > 0)
                <tab name="Published ({{ $published->total() }})" id="published">
                    @foreach($published as $page)
                        @include('chief::back.pages._partials._rowitem')
                        @include('chief::back.pages._partials.delete-modal')
                    @endforeach
                    <div class="text-center">
                        {!! $published->render() !!}
                    </div>
                </tab>
            @endif

            @if( $archived->total() > 0)
                <tab name="Archief ({{ $archived->total() }})" id="archived">
                    @foreach($archived as $page)
                        @include('chief::back.pages._partials._rowitem')
                        @include('chief::back.pages._partials.delete-modal')
                    @endforeach
                    <div class="text-center">
                        {!! $archived->render() !!}
                    </div>
                </tab>
            @endif
        </tabs>
    @endif

    @include('chief::back.pages._partials.create-modal')
@stop