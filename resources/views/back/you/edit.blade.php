@extends('back._layouts.master')

@section('page-title', $user->fullname)

@chiefheader
@slot('title', 'Bewerk jouw profiel')
<div class="inline-group">
    <button data-submit-form="updateForm" type="button" class="btn btn-o-primary">Bewaar</button>
</div>
@endchiefheader

@section('content')

    <form id="updateForm" action="{{ route('back.you.update',$user->id) }}" method="POST">
        {!! csrf_field() !!}
        <input type="hidden" name="_method" value="PUT">

        @include('back.you._form')

        <button type="submit" class="btn btn-primary right">Bewaar</button>
    </form>

@endsection