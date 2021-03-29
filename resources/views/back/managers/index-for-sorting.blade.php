@extends('chief::back._layouts.master')

@section('page-title')
    @adminConfig('pageTitle')
@endsection

@section('header')
    @include('chief::back.managers._index._header')
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
            <div class="row gutter-s"
                data-sort-on-load
                data-sort-route="{{ $manager->route('sort-index') }}"
                id="js-sortable">
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
