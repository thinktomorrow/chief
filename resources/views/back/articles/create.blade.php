@extends('back._layouts.master')

@section('page-title','Voeg nieuw artikel toe')

@component('back._layouts._partials.header')
    @slot('title', 'Nieuw artikel')
        <div class="center-y right inline-group">
            <a href="#" class="btn btn-o-primary">Preview</a>
            <div class="btn-group">
                <button type="button" class="btn btn-primary">Publiceer</button>
                <div class="dropdown">
                    <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle btn btn-primary">
                        <i class="icon icon-chevron-down"></i>
                        <div class="dropdown-menu">
                            <div><a href="#">Als draft</a></div>
                            <div><a href="#">Onmiddellijk</a></div>
                        </div>
                    </button>
                </div>
            </div>
        </div>
@endcomponent

@section('content')

	<form method="POST" action="{{ route('back.articles.store') }}" enctype="multipart/form-data" role="form">
		{{ csrf_field() }}

		@include('back.articles._form')
	</form>

@stop