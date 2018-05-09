@extends('back._layouts.master')

@section('page-title','Voeg nieuw pagina toe')

@component('back._layouts._partials.header')
    @slot('title', 'Nieuw pagina')
        <div class="center-y right inline-group">
            <div class="btn-group">
                <button @click="showModal('publication-now-page')" type="button" class="btn btn-primary">Opslaan</button>
            </div>
        </div>
@endcomponent

@section('content')

	<form method="POST" action="{{ route('back.pages.store') }}" enctype="multipart/form-data" role="form">
		{{ csrf_field() }}

		@include('back.pages._form')
        @include('back.pages._partials.modal')

	</form>

@stop