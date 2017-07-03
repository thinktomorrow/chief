@extends('back._layouts.master')

@section('content')

	@can('view_users')
		@include('back._partials.users')
	@endcan

@stop
