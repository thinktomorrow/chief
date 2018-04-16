@extends('back._layouts.master')

@section('page-title','Voeg nieuw artikel toe')

@component('back._layouts._partials.header')
    @slot('title', 'Nieuw artikel')
        <div class="btn-group right">
            <button type="button" class="btn btn-primary">Action</button>
            <div class="dropdown">
                <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle btn btn-primary">
                    <i class="icon icon-chevron-down"></i>
                    <div class="dropdown-menu">
                        <div><a href="#">Action</a></div>
                        <div><a href="#">Another action</a></div>
                        <div><a href="#">Something else here</a></div>
                        <div><a href="#">Separated link</a></div>
                    </div>
                </button>
            </div>

@endcomponent

@push('custom-styles')
	<link rel="stylesheet" href="{{ asset('assets/back/vendor/redactor2/redactor.css') }}">
	<link href="{{ asset('assets/back/theme/vendor/plugins/datepicker/css/bootstrap-datetimepicker.css') }}"
	      rel="stylesheet" type="text/css">
@endpush

@push('custom-scripts')
	<script src="{{ asset('assets/back/vendor/redactor2/redactor.js') }}"></script>
	<script>
		;(function ($) {

			$('.redactor-editor').redactor({
				focus: true,
				pastePlainText: true,
				buttons: ['html', 'formatting', 'bold', 'italic',
					'unorderedlist', 'orderedlist', 'outdent', 'indent',
					'link', 'alignment', 'horizontalrule']
			});

		})(jQuery);

	</script>

@endpush

@section('content')

	<form method="POST" action="{{ route('back.articles.store') }}" enctype="multipart/form-data" role="form">
		{{ csrf_field() }}

		@include('back.articles._form')

	</form>

@stop
