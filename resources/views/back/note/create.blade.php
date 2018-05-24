@extends('chief::back._layouts.master')

@section('page-title','Nieuwe note')

@section('breadcrumb')
    <li class="crumb-link">
        <a href="{{ route('notes.index') }}">Notes</a>
    </li>
    <li class="crumb-active">
        nieuwe note
    </li>
@endsection

@section('content')

    {!! Form::model($note, ['method' => 'POST', 'id'=>'admin-form', 'route'=>['notes.store']]) !!}

        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title hidden-xs">Melding aanmaken</span>
            </div>
            <div class="panel-body">
                <div class="admin-form">

                    @include('chief::back.note._form')

                </div>
            </div>
            <div class="panel-footer clearfix">
                <button class="btn btn-success btn-lg pull-right" type="submit">Maak melding aan</button>
                <a class="btn btn-link pull-right" href="{{ route('notes.index') }}"><< Terug</a>
            </div>
        </div>

    {!! Form::close() !!}

@stop

@push('custom-scripts')
<script>
    $( function() {
		$( "#start_at" ).datepicker({
			dateFormat: "dd/mm/yy"
        });
		$( "#end_at" ).datepicker({
			dateFormat: "dd/mm/yy"
		});
	} );
</script>
@endpush