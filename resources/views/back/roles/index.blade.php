@extends('back._layouts.master')

@section('title', '| Edit Roles')

@section('content')

	@section('page-title')
	Role management
	@stop

	@section('topbar-right')
		@can('add_users')
			<button id="btnNewRole" class="btn btn-success">
				<i class="fa fa-plus mr10" aria-hidden="true"></i>
				Rol toevoegen
			</button>
			<button id="btnCancelRole" class="btn btn-info hidden">
				<i class="fa fa-times mr10" aria-hidden="true"></i>
				Annuleren
			</button>
		@endcan
	@stop
	@include('back.roles._partials.roles')

@endsection
