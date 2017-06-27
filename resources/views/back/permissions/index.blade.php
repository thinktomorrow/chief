@extends('back._layouts.master')

@section('title', '| Permissions')

@section('content')

	<div class="col-lg-10 col-lg-offset-1">
		<h1><i class="fa fa-key"></i>Available Permissions

			<a href="{{ route('users.index') }}" class="btn btn-default pull-right">Users</a>
			<a href="{{ route('roles.index') }}" class="btn btn-default pull-right">Roles</a></h1>
		<hr>
		<div class="table-responsive">
			<table class="table table-bordered table-striped">

				<thead>
				<tr>
					<th>Permissions</th>
					<th>Operation</th>
				</tr>
				</thead>
				<tbody>
				@foreach ($permissions as $permission)
					<tr>
						<td>{{ $permission->name }}</td>
						<td>
							<a href="{{ URL::to('admin/permissions/'.$permission->id.'/edit') }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>

							<form action="{{ route('permissions.destroy', $permission->id) }}" method="POST">
								<input name="_method" type="hidden" value="DELETE">
								{!! csrf_field() !!}
								<button type="submit" value="Submit" class="btn btn-danger">Delete</button>
							</form>

						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>

		<a href="{{ URL::to('admin/permissions/create') }}" class="btn btn-success">Add Permission</a>

	</div>

@endsection