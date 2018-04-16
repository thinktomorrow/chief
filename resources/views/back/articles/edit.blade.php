@extends('back._layouts.master')

@section('page-title','Pas "' .$article->title .'" aan')

@section('content')

  <form method="POST" action="{{ route('back.articles.update', $article->id) }}" enctype="multipart/form-data" role="form">
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="PUT">

    @include('back.articles._form')

  </form>
@stop

