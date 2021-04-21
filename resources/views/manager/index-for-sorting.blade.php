@extends('chief::layout.master')

@section('page-title')
    @adminConfig('indexTitle')
@endsection

@section('header')
    <div class="container">
        @component('chief::layout._partials.header')
            @slot('title')
                @adminConfig('indexTitle')
            @endslot
        @endcomponent
    </div>
@stop

@section('content')
    <div class="container">
        <div class="row gutter-6">
            <div class="w-full lg:w-2/3 window window-white">

                <div
                        class="window window-white"
                        data-sort-on-load
                        data-sort-route="{{ $manager->route('sort-index') }}"
                        id="js-sortable"
                >
                    @foreach($models as $model)
                        <div class="flex" data-sortable-id="{{ $model->id }}" style="cursor:grab;">
                            <div class="bg-grey-50 bg-white border border-grey-100 mb-1 p-2 rounded" style="flex:1 1 0;">
                                <span class="">@adminConfig('pageTitle')</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="w-full lg:w-1/3">
                <div class="stack">
                    <div class="mb-8">
                        <a class="btn btn-primary" href="{{ $manager->route('index') }}">Stop met sorteren</a>
                        <p class="font-xs mt-2">Sleep de blokken in de gewenste volgorde. De volgorde wordt
                            automatisch bewaard.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
