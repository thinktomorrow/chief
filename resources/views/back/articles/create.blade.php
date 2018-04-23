@extends('back._layouts.master')

@section('page-title','Voeg nieuw artikel toe')

@component('back._layouts._partials.header')
    @slot('title', 'Nieuw artikel')
        <div class="center-y right inline-group">
            <a href="#" class="btn btn-o-primary">Preview</a>
            <div class="btn-group">
                <button @click="showModal('publication-now-article')" type="button" class="btn btn-primary">Opslaan</button>
                <div class="dropdown">
                    <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle btn btn-primary">
                        <i class="icon icon-chevron-down"></i>
                        <div class="dropdown-menu">
                            <div><a>Als draft</a></div>
                            <div><a @click="showModal('publication-article')">Publiceer</a></div>
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
        @include('back.articles._partials.modal')
        @include('back.articles._partials.sidebar')

	</form>

@stop