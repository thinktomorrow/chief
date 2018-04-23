@extends('back._layouts.master')

@section('page-title','Pas "' .$article->title .'" aan')


@component('back._layouts._partials.header')
    @slot('title', 'Pas "' .$article->title .'" aan')
        <div class="center-y right inline-group">
            <a href="#" class="btn btn-o-primary">Preview</a>
            <div class="btn-group">
                <button @click="showModal('publication-article')" type="button" class="btn btn-primary">Wijzigingen opslaan</button>
                <div class="dropdown">
                    <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle btn btn-primary">
                        <i class="icon icon-chevron-down"></i>
                        <div class="dropdown-menu">
                            <div><a href="#">Haal artikel offline</a></div>
                            <div><a @click="showModal('delete-article')">Verwijder artikel</a></div>
                        </div>
                    </button>
                </div>
            </div>
        </div>
@endcomponent

@section('content')

  <form method="POST" action="{{ route('back.articles.update', $article->id) }}" enctype="multipart/form-data" role="form">
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="PUT">

    @include('back.articles._form')

  </form>

  <script>
  $(document).keydown(function(event) {
      if (!(event.which == 115 && event.ctrlKey) && !(event.which == 19)) return true;
      alert("Ctrl-S pressed");
      event.preventDefault();
      return false;
  });
  </script>
@stop

