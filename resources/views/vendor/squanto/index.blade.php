@extends(config('squanto.template'))

@section('page-title')
    Teksten
@stop

@component('chief.back._layouts._partials.header')
    @slot('title','Teksten')
    @if(admin()->isSquantoDeveloper())
        <a href="{{ route('squanto.lines.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> add new line</a>
    @endif
@endcomponent

@section('content')

    <div class="card-group-title"><span class="inline-xs">Pages</span></div>
    <div class="row gutter card-group left">
        @foreach($pages->filter(function($page){ return in_array($page->key,[]); }) as $page)
            @include('squanto::_rowitem', ['show_cart_subnav' => false])
        @endforeach
    </div>

    <div class="card-group-title"><span class="inline-xs">Content</span></div>
    <div class="row gutter card-group left">
        @foreach($pages->reject(function($page){ return in_array($page->key,[]); }) as $page)
            @include('squanto::_rowitem')
        @endforeach
    </div>
@stop
