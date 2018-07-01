@extends('chief::back._layouts.master')

@section('page-title','Voeg nieuw pagina toe')

@component('chief::back._layouts._partials.header')
    @slot('title', 'Een ' . $page->collectionDetails()->singular.' toevoegen.')
    <button data-submit-form="createForm" type="button" class="btn btn-primary">Toevoegen</button>
@endcomponent

@section('content')

	<form id="createForm" method="POST" action="{{ route('chief.back.pages.store', $page->collectionKey()) }}" enctype="multipart/form-data" role="form">
		{{ csrf_field() }}

		@include('chief::back.pages._form')
        @include('chief::back.pages._partials.modal')

	</form>

@stop


{{-- TODO: this is disabled because we do cannot manage media in create mode. Better is to direct to edit as soon as possible. --}}
{{--@push('custom-scripts-after-vue')--}}
	{{--@include('chief::back._layouts._partials.editor-script')--}}
{{--@endpush--}}