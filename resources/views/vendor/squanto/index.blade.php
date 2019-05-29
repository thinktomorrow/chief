@extends('chief::back._layouts.master')

@section('page-title')
    Teksten
@stop

@component('chief::back._layouts._partials.header')
    @slot('title','Teksten')
    @can('create-squanto')
        <a href="{{ route('squanto.lines.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> add new line</a>
    @endcan
@endcomponent

@section('content')
    <section class="formgroup stack">
        <h2><span class="inline-xs">Algemene teksten</span></h2>
        <div class="row gutter">
            @foreach($pages->reject(function($page){ return in_array($page->key,[]); }) as $page)
                @include('squanto::_rowitem')
            @endforeach
        </div>
    </section>
@stop
