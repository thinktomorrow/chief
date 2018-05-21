@extends('back._layouts.master')

@section('page-title', $user->fullname)

@chiefheader
	@slot('title', $user->fullname)
	<div class="inline-group">
		<button data-submit-form="updateForm" type="button" class="btn btn-o-primary">Bewaar</button>
		<options-dropdown class="inline-block">
			<div v-cloak>
				<div>
					<a class="block inset-s" href="{{ route('back.invites.resend', $user->id) }}">Stuur nieuwe uitnodiging</a>
				</div>
				<hr>
				<div class="inset-s font-s">
					<p>Om {{ $user->firstname }} tijdelijk de toegang <br>te ontnemen, kan je de account <a class="text-error">blokkeren</a>.</p>
				</div>
			</div>
		</options-dropdown>
	</div>
@endchiefheader

@section('content')

	<form id="updateForm" action="{{ route('back.users.update',$user->id) }}" method="POST">
		{!! csrf_field() !!}
		<input type="hidden" name="_method" value="PUT">

		@include('back.users._form')

		<button type="submit" class="btn btn-primary right">Bewaar aanpassingen</button>
	</form>

@endsection