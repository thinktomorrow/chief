@extends('chief::back._layouts.master')

@section('page-title','Pas "' .$page->title .'" aan')


@component('chief.back._layouts._partials.header')
    @slot('title', 'Pas "' .$page->title .'" aan')
    <a href="#" class="btn btn-o-primary inline-s">Preview</a>
    <div class="btn-group">
        <button @click="showModal('publication-now-page')" type="button" class="btn btn-primary">Wijzigingen opslaan
        </button><button-dropdown class="inline-block btn-group-last" btn_name="<i class='icon icon-chevron-down'></i>">
            <div v-cloak>
                <a href="#" class="block squished-s --link-with-bg">Haal pagina offline</a>
                <a href="#" class="block squished-s --link-with-bg" @click="showModal('delete-page')">Verwijder pagina</a>
            </div>
        </button-dropdown>
    </div>
@endcomponent

@section('content')

  <form method="POST" action="{{ route('chief.back.pages.update', $page->id) }}" enctype="multipart/form-data" role="form">
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="PUT">

    @include('chief::back.pages._update_form')
    @include('chief::back.pages._partials.modal')
    @include('chief::back.pages._partials.sidebar')

  </form>
@stop
