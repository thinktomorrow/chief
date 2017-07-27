@extends('back._layouts.master')

@section('page-title','Melding bewerken')

@section('breadcrumb')
    <li class="crumb-link">
        <a href="{{ route('notes.index') }}">Meldingen</a>
    </li>
    <li class="crumb-active">
        bewerken
    </li>
@endsection

@section('content')

			{!! Form::model($note, ['method' => 'PUT', 'id'=>'admin-form', 'route'=>['notes.update', $note->id]]) !!}

			<div class="panel">
				<div class="panel-heading">
					<span class="panel-title hidden-xs">Melding bewerken</span>
				</div>
				<div class="panel-body">
					<div class="admin-form">

						@include('back.note._form')

					</div>
				</div>
				<div class="panel-footer clearfix">
					<button class="btn btn-success btn-lg pull-right" type="submit">Aanpassen</button>
					<a class="btn btn-link pull-right" href="{{ route('notes.index') }}"><< Terug</a>
				</div>
			</div>

			{!! Form::close() !!}



@stop

@push('custom-scripts')
<script>
	$( function() {
		var $start_at = $( "#start_at" );
		$start_at.datepicker({
			dateFormat: "dd/mm/yy"
		});
		$start_at.datepicker('setDate', "{{ $note->start_at }}");

		var $end_at = $( "#end_at" );
		$end_at.datepicker({
			dateFormat: "dd/mm/yy"
		});
		$end_at.datepicker('setDate', "{{ $note->end_at }}");

	} );
</script>
@endpush