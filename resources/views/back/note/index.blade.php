@extends('back._layouts.master')

@section('page-title', 'Note')

@section('breadcrumb')
	<li class="crumb-link">
		<a href="{{ route('notes.index') }}">Notes</a>
	</li>
@endsection

@section('topbar-right')
<div class="pull-right">
	<a href="{{ route('notes.create') }}" class="btn btn-sm btn-rounded btn-info">nieuwe note</a>
</div>
@stop

@push('custom-styles')
	{!! Charts::assets() !!}
@endpush

@section('content')
	<div class="panel">
		<div class="panel-body pn">
			{!! $chart->render() !!}

			<table class="table admin-form">
				<thead>
					<tr class="bg-light">
						<th>Type</th>
						<th>Content</th>
						<th>Periode</th>
						<th>Online</th>
						<th class="text-right"></th>
					</tr>
				</thead>

				<tbody>
				@foreach($notes as $note)
					<tr>
						<td>{!! $note->renderTypeBadge()  !!}</td>
						<td>{{ $note->content }}</td>
						<td>{{ $note->start_at->format('d/m/Y') }} - {{ $note->end_at->format('d/m/Y') }}</td>
						<td>
							<div class="switch switch-success round switch-inline">
								{!! Form::checkbox('published',1,$note->isPublished(),['data-publish-toggle'=>$note->id,'id' => "switch{$note->id}"]) !!}
								<label title="{{ $note->isPublished()?'Online':'Offline' }}" for="switch{{$note->id}}"></label>
							</div>
						</td>
						<td class="text-right">
							<a href="{{ route('notes.edit', $note->id) }}">Edit</a>
							|
							<a data-action-delete href="#remove-note-modal-{{$note->id}}">Remove</a>
						</td>
					</tr>
					@include('back.note._partials._deletemodal', $note)
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
@stop
@section('custom-scripts')
	<script>
		jQuery(document).ready(function ($) {

			var $triggers = $('[data-publish-toggle]'),
				url = "{{route('notes.publish')}}"

			$triggers.on('click', function () {
				var $this = $(this);

				$.ajax({
					data: {
						id: $this.data('publish-toggle'),
						checkboxStatus: this.checked,
						_token: '{!! csrf_token() !!}'
					},
					url: url,
					type: 'POST',
					dataType: 'json',
					success: function (data) {
						var title =  data.published ? 'online' : 'offline';
						$this.parent().find('label').prop('title', title);
					}
				});
			});
		});
		$(document).ready(function() {
			var $toggle = $("[data-action-delete]");
			$toggle.magnificPopup();
		});
	</script>
@stop