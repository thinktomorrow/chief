@extends('admin._layouts.master')

@section('page-title')
	Typeform entry
@stop

@section('content')

		<div class="panel">
			<div class="panel-heading">
                  <span class="panel-icon">
                    <i class="fa fa-info"></i>
                  </span>
				<span class="panel-title">Form answers</span>
			</div>
			<div class="panel-body pb5 columns large-4">

				@foreach($entry->typeformAnswer as $answer)
					@if($answer->answer != null)
						<div class="columns col-md-4">{{$answer->typeformQuestion->question}}:</div>
						<div class="columns">{{$answer->answer}}</div>
					@endif
				@endforeach
			</div>
		</div>


@stop