@extends('back._layouts.master')

@section('page-title', 'Rollen')

@chiefheader
	@slot('title', 'Rollen')
	<a href="{{ route('back.roles.create') }}" class="btn btn-link text-primary">een nieuwe rol toevoegen</a>
@endchiefheader

@section('content')
	@foreach($roles as $role)
		<a class="block stack panel panel-default inset-s" href="{{ route('back.roles.edit', $role->id) }}">{{ $role->name }}</a>
	@endforeach
@stop