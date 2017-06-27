@extends('back._layouts.master')

@section('content')

	@role('Superadmin')
		@include('back._partials.users')
	@endrole

@stop
