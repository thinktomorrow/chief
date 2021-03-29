@extends('chief::back._layouts.master')

@section('page-title', $user->fullname)

@chiefheader
    @slot('title', $user->fullname)

    <div class="inline-group flex items-center">
        {!! $user->present()->enabledAsLabel() !!}

        @if($user->isEnabled())
            <button data-submit-form="updateForm" type="button" class="btn btn-primary">Bewaar</button>
        @endif

        <options-dropdown>
            <div v-cloak class="dropdown-content">
                <a class="dropdown-link" href="{{ route('chief.back.invites.resend', $user->id) }}">Stuur nieuwe uitnodiging</a>

                @if($user->isEnabled())
                    <form method="POST" action="{{ route('chief.back.users.disable', $user->id) }}" class="mb-0">
                        {{ csrf_field() }}
                        {{-- <p>Om {{ $user->firstname }} tijdelijk de toegang <br>te ontnemen, kan je de account <input type="submit" class="text-error" value="blokkeren">.</p> --}}
                        <a><input type="submit" class="text-error" value="{{ $user->firstname }} blokkeren"></a>
                    </form>
                @else
                    <form method="POST" action="{{ route('chief.back.users.enable', $user->id) }}" class="mb-0">
                        {{ csrf_field() }}
                        {{-- <p>{{ $user->firstname }} is momenteel geblokkeerd. <br> <input type="submit" class="text-primary" value="Verleen opnieuw toegang">.</p> --}}
                        <a><input type="submit" class="text-warning" value="{{ $user->firstname }} deblokkeren"></a>
                    </form>
                @endif
            </div>
        </options-dropdown>
    </div>
@endchiefheader

@section('content')
    <form id="updateForm" action="{{ route('chief.back.users.update',$user->id) }}" method="POST">
        {!! csrf_field() !!}

        <input type="hidden" name="_method" value="PUT">

        @include('chief::admin.users._form')

        <button type="submit" class="btn btn-primary right">Bewaar aanpassingen</button>
    </form>
@endsection
