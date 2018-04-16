@extends('back._layouts.master')

@section('page-title','Voeg nieuw artikel toe')

@component('back._layouts._partials.header')
    @slot('title', 'Nieuw artikel')
        <div class="btn-group right">
            <button type="button" class="btn btn-primary">Opslaan</button>
            <div class="dropdown">
                <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle btn btn-primary">
                    <i class="icon icon-chevron-down"></i>
                    <div class="dropdown-menu">
                        <div><a href="#">Als draft</a></div>
                        <div><a href="#">Publiceer</a></div>
                    </div>
                </button>
            </div>
        </div>
@endcomponent

@section('content')

	<form method="POST" action="{{ route('back.articles.store') }}" enctype="multipart/form-data" role="form">
		{{ csrf_field() }}

		@include('back.articles._form')
	</form>

@stop