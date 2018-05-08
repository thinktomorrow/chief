@extends('back._layouts.master')

@section('page-title','Voeg nieuw artikel toe')

@component('back._layouts._partials.header')
    @slot('title', 'Nieuw artikel')
        <div class="center-y right inline-group">
            <a href="#" class="btn btn-o-primary">Preview</a>
            <div class="btn-group">
                <button @click="showModal('publication-article')" type="button" class="btn btn-primary">Publiceer</button>
                <button-dropdown class="inline-block btn-group-last" btn_name="<i class='icon icon-chevron-down'></i>">
                    <div v-cloak>
                        <a href="#" class="block squished-s">Als draft</a>
                        <a href="#" class="block squished-s" @click="showModal('publication-now-article')">Onmiddellijk</a>
                    </div>
                </button-dropdown>
            </div>
        </div>
@endcomponent

@section('content')

	<form method="POST" action="{{ route('back.articles.store') }}" enctype="multipart/form-data" role="form">
		{{ csrf_field() }}

		@include('back.articles._form')
        @include('back.articles._partials.modal')

        {{-- @include('back.articles._partials.sidebar') --}}

	</form>

@stop