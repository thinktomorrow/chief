@extends('chief::back._layouts.master')

@section('page-title', "Pagina's")

@component('chief::back._layouts._partials.header')
    @slot('title', $collectionDetails->plural)
        <div class="inline-group-s">
            <a href="{{ route('chief.back.pages.create', $collectionDetails->key) }}" class="btn btn-primary">
                <i class="icon icon-plus"></i>
                Voeg een {{ $collectionDetails->singular }} toe
            </a>
        </div>
    @endcomponent

    @section('content')

        <tabs v-cloak>
            <tab name="Drafts ({{$drafts->count()}})" id="drafts">
                @if( ! $drafts->isEmpty())
                    @foreach($drafts as $page)
                        @include('chief::back.pages._partials._rowitem')
                        @include('chief::back.pages._partials.delete-modal')
                    @endforeach
                    <div class="text-center">
                        {!! $drafts->render() !!}
                    </div>
                @else
                    <a href="{{ route('chief.back.pages.create', $collectionDetails->key) }}" class="btn btn-primary">
                        <i class="icon icon-zap icon-fw"></i> Tijd om aan de slag te gaan
                    </a>
                @endif
            </tab>

            @if( ! $published->isEmpty())
                <tab name="Published ({{ $published->count() }})" id="published">
                    @foreach($published as $page)
                        @include('chief::back.pages._partials._rowitem')
                        @include('chief::back.pages._partials.delete-modal')
                    @endforeach
                    <div class="text-center">
                        {!! $published->render() !!}
                    </div>
                </tab>
            @endif
            @if( ! $archived->isEmpty())
                <tab name="Archief ({{ $archived->count() }})" id="archived">
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
@stop

@push('custom-scripts')
    <script>
    // SHOW OR HIDE PUBLISH BUTTON
    $("[class^='showPublishOptions-'], [class*='showPublishOptions-']").click(function(){
        var id = this.dataset.publishId;
        $('.publishActions-'+id).removeClass('--hidden');
        $('.showPublishOptions-'+id).addClass('--hidden');
    });
    $('.noPublish').click(function(){
        var id = this.dataset.publishId;
        $('.publishActions-'+id).addClass('--hidden');
        $('.showPublishOptions-'+id).removeClass('--hidden');
    });
</script>
@endpush