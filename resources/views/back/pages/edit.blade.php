@extends('back._layouts.master')

@section('page-title','Pas "' .$page->title .'" aan')


@component('back._layouts._partials.header')
    @slot('title', 'Pas "' .$page->title .'" aan')
        <div class="center-y right inline-group">
            <a href="#" class="btn btn-o-primary">Preview</a>
            <div class="btn-group">
                <button @click="showModal('publication-now-page')" type="button" class="btn btn-primary">Wijzigingen opslaan</button>
                <div class="dropdown">
                    <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle btn btn-primary">
                        <i class="icon icon-chevron-down"></i>
                        <div class="dropdown-menu">
                            <div><a href="#">Haal pagina offline</a></div>
                            <div><a @click="showModal('delete-page')">Verwijder pagina</a></div>
                        </div>
                    </button>
                </div>
            </div>
        </div>
@endcomponent

@section('content')

  <form method="POST" action="{{ route('back.pages.update', $page->id) }}" enctype="multipart/form-data" role="form">
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="PUT">

    @include('back.pages._update_form')
    @include('back.pages._partials.modal')
    @include('back.pages._partials.sidebar')

  </form>
@stop
