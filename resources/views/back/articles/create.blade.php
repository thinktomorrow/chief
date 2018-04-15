@extends('back._layouts.master')

@section('page-title','Voeg nieuw artikel toe')

@component('back._layouts._partials.header')
    @slot('title', 'Nieuw artikel')
    <div class="btn-group relative">
		<button type="button" class="btn btn-primary squished">Bewaar</button>
		<button type="button" class="btn btn-primary squished dropdown-toggle" data-toggle="dropdown">
			<span class="icon icon-chevron-down"></span>
		</button>
		<ul class="dropdown-menu" role="menu">
			<li><a href="#">As draft</a></li>
			<li><a href="#">In review</a></li>
		</ul>
	</div>

@endcomponent

@section('content')

	<form method="POST" action="{{ route('back.articles.store') }}" enctype="multipart/form-data" role="form">
		{{ csrf_field() }}

		@include('back.articles._form')

	</form>

@stop
