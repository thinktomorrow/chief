@extends('chief::back._layouts.master')

@section('page-title')
    Dashboard
@stop

@section('topbar-right')

@stop

@section('content')
    <div class="row gutter stack-l">
        <div class="column-4 stretched-xl">
            <h1>Welkom op je dashboard, {{ Auth::user()->firstname }}</h1>
            <p>Don't try to follow trends. Create them</p>
        </div>

        <div class="gutter column-8 inset right">
            @foreach(\Thinktomorrow\Chief\Pages\Page::availableCollections() as $key => $collection)
                <div class="column-6">
                    <div class="panel panel-default --raised">
                        <div class="panel-body inset">
                            <div class="btn btn-o-primary btn-circle">
                                <i class="icon icon-box"></i>
                            </div>
                            <div class="stack">
                                <h1 class="--remove-margin">{{ (new $collection->class)->count() }}</h1>
                                <p>{{ $collection->plural }}</p>
                                <a class="btn btn-secondary" href="{{ route('chief.back.pages.index', $key) }}">Ga naar {{ $collection->plural }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@stop