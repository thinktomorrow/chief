@extends('chief::layout.master')

@section('page-title')
    {{ $resource->getIndexTitle() }}
@endsection

@section('header')
    <div class="container">
        @component('chief::layout._partials.header')
            @slot('title')
                Volgorde aanpassen
            @endslot
        @endcomponent
    </div>
@stop

@section('content')
    <div class="container">
        <div class="row gutter-3">
            <div class="w-full lg:w-2/3">
                <div class="card">
                    <div
                        id="js-sortable"
                        data-sort-on-load data-sort-route="{{ $manager->route('sort-index') }}"
                        class="row-start-stretch gutter-1"
                    >
                        @foreach($models as $model)
                            <div data-sortable-id="{{ $model->id }}" class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4">
                                <div class="h-full p-3 border rounded-md cursor-move transition-75 bg-grey-50 border-grey-100 hover:bg-grey-100">
                                    <p class="text-sm display-base display-dark">
                                        {{ $resource->getPageTitle($model) }}
                                    </p>
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
                <div class="card">
                    <div class="space-y-4">
                        <a href="{{ $manager->route('index') }}" title="Overzicht" class="btn btn-primary">
                            Overzicht
                        </a>

                        <p class="text-sm text-grey-700">
                            Sleep de blokken in de gewenste volgorde. De volgorde wordt automatisch bewaard.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
