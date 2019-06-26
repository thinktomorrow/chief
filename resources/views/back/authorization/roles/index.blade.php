@extends('chief::back._layouts.master')

@section('page-title', 'Rollen')

@chiefheader
	@slot('title', 'Rollen')
	<a href="{{ route('chief.back.roles.create') }}" class="btn btn-link text-primary">een nieuwe rol toevoegen</a>
@endchiefheader

@section('content')
	@foreach($roles as $role)
		<a class="block stack border border-grey-100 rounded inset-s" href="{{ route('chief.back.roles.edit', $role->id) }}">{{ $role->name }}</a>
	@endforeach
@stop