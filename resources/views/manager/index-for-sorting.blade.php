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
        <div class="row gutter-3">
            <div class="w-full lg:w-2/3">
                <div class="window window-white window-md">
                    <div id="js-sortable" data-sort-on-load data-sort-route="{{ $manager->route('sort-index') }}" class="row gutter-2">
                        @foreach($models as $model)
                            <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4" data-sortable-id="{{ $model->id }}">
                                <div class="p-4 border rounded-lg cursor-move bg-grey-50 border-grey-100">
                                    <span class="font-semibold text-grey-900">@adminConfig('pageTitle')</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if($models instanceof \Illuminate\Contracts\Pagination\Paginator)
                    {!! $models->links('chief::pagination.default') !!}
                @endif
            </div>

            <div class="w-full lg:w-1/3">
                <div class="window window-grey window-md">
                    <div class="space-y-4">
                        <a class="btn btn-primary" href="{{ $manager->route('index') }}">Stop met sorteren</a>

                        <p class="text-sm text-grey-700">
                            Sleep de blokken in de gewenste volgorde. De volgorde wordt automatisch bewaard.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
