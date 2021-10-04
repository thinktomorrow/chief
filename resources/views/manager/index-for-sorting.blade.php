@extends('chief::layout.master')

@section('page-title')
    @adminConfig('indexTitle')
@endsection

@section('header')
    <div class="container">
        @component('chief::layout._partials.header')
            @slot('title')
                Volgorde van {{ strtolower($model->adminConfig()->getIndexTitle()) }} aanpassen
            @endslot
        @endcomponent
    </div>
@stop

@section('content')
    <div class="container">
        <div class="row gutter-3">
            <div class="w-full lg:w-2/3">
                <div class="overflow-hidden window window-white window-xs">
                    <div
                        id="js-sortable"
                        data-sort-on-load data-sort-route="{{ $manager->route('sort-index') }}"
                        class="row-start-stretch gutter-4"
                    >
                        @foreach($models as $model)
                            <div class="w-full border sm:w-1/2 md:w-1/3 lg:w-1/4 border-grey-100" data-sortable-id="{{ $model->id }}">
                                <div class="rounded-lg cursor-move">
                                    <p class="text-sm font-semibold text-grey-900">
                                        @adminConfig('pageTitle')
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
                <div class="window window-grey window-md">
                    <div class="space-y-4">
                        <a class="btn btn-primary" href="{{ $manager->route('index') }}">
                            Terug naar overzicht
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
