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

            <!-- // ORDER COUNTER -->
            <div class="column-6">
                <div class="panel panel-default --raised disabled">
                    <div class="panel-body inset">
                        <div class="btn btn-o-primary btn-circle">
                            <i class="icon icon-box"></i>
                        </div>
                        <div class="stack">
                            <h1 class="--remove-margin">3</h1>
                            <p>Producten</p>
                            <a class="btn btn-secondary">Ga naar producten</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // PRODUCT COUNTER -->
            <div class="column-6">
                <div class="panel panel-default --raised disabled">
                    <div class="panel-body inset">
                        <div class="btn btn-o-primary btn-circle">
                            <i class="icon icon-book"></i>
                        </div>
                        <div class="stack">
                            <h1 class="--remove-margin">4</h1>
                            <p>Diensten</p>
                            <a class="btn btn-secondary">Ga naar diensten</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // USER COUNTER -->
            <div class="column-6">
                <div class="panel panel-default --raised">
                    <div class="panel-body inset">
                        <div class="btn btn-o-primary btn-circle">
                            <i class="icon icon-file"></i>
                        </div>
                        <div class="stack">
                            <h1 class="--remove-margin">5</h1>
                            <p>Pagina's</p>
                            <a href="{{ route('chief.back.pages.index', 'statics') }}" class="btn btn-secondary">Ga naar pagina's</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop