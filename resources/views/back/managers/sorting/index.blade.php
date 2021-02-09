
@extends('chief::back._layouts.master')

@section('page-title', $modelManager->details()->plural)

@component('chief::back._layouts._partials.header')
    @slot('title', 'Sortering ' . $modelManager->details()->plural)
@endcomponent

@section('content')

    <div class="stack">
        <div class="mb-8">
            <a class="btn btn-primary" href="{{ $modelManager->route('index') }}">Stop met sorteren</a>
            <p class="font-xs mt-2">Sleep de blokken in de gewenste volgorde. De volgorde wordt automatisch bewaard.</p>
        </div>
    </div>

    <div class="row gutter-l stack">
        <div class="column-12">
            <div
                data-sortable-type="{{ get_class($modelManager->modelInstance()) }}"
                id="js-sortable"
                data-sort-on-load
                class="row gutter-s"
            >
                @foreach($managers as $manager)
                    <div class="s-column-3 inset-xs flex" data-sortable-id="{{ $manager->existingModel()->id }}" style="cursor:grab;">
                        <div class="bg-white border border-grey-100 rounded inset-s" style="flex:1 1 0%;">
                            <span class="text-black font-bold">{!! $manager->details()->title !!}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@stop
