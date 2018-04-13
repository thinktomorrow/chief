@extends('back._layouts.master')

@component('back._layouts._partials.header')
@slot('title','Prototype')
<button>Fake button</button>
@endcomponent

@section('content')

    @for($i=0;$i<10;$i++)
        @include('prototype._rowitem')
    @endfor

@stop