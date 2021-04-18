@extends('chief::back._layouts.master')

@section('page-title')
    @adminConfig('pageTitle')
@endsection

@section('header')
    @component('chief::back._layouts._partials.header')
        @slot('title')
            @adminConfig('indexTitle')
        @endslot

        @slot('breadcrumbs')
            <a href="{{ route('chief.back.dashboard') }}" class="link link-primary">
                <x-icon-label type="back">Dashboard</x-icon-label>
            </a>
        @endslot

        @adminCan('create')
            <a href="@adminRoute('create')" class="btn btn-primary">
                <x-icon-label type="add">Voeg een @adminConfig('modelName') toe</x-icon-label>
            </a>
        @endAdminCan
    @endcomponent
@stop

@section('content')
    <div class="stack">
        <div class="mb-8">
            <a class="btn btn-primary" href="{{ $manager->route('index') }}">Stop met sorteren</a>
            <p class="font-xs mt-2">Sleep de blokken in de gewenste volgorde. De volgorde wordt automatisch bewaard.</p>
        </div>
    </div>

    <div class="row gutter-l stack">
        <div class="column-12">
            <div
                class="row gutter-s"
                data-sort-on-load
                data-sort-route="{{ $manager->route('sort-index') }}"
                id="js-sortable"
            >
                    @foreach($models as $model)
                        <div class="s-column-3 inset-xs flex" data-sortable-id="{{ $model->id }}" style="cursor:grab;">
                            <div class="bg-white border border-grey-100 rounded inset-s" style="flex:1 1 0;">
                                <span class="text-black font-bold">@adminConfig('pageTitle')</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@stop
