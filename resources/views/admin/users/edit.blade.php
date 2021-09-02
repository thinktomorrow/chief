@extends('chief::layout.master')

@section('page-title', $user->fullname)

@section('header')
    <div class="container-sm">
        @component('chief::layout._partials.header')
            @slot('title', $user->fullname)

            @slot('breadcrumbs')
                <a href="{{ route('chief.back.users.index') }}" class="link link-primary">
                    <x-chief-icon-label type="back">Terug naar admins</x-chief-icon-label>
                </a>
            @endslot

            <div class="flex items-center space-x-4">
                {!! $user->present()->enabledAsLabel() !!}

                @if($user->isEnabled())
                    <button form="updateForm" type="submit" class="btn btn-primary">Opslaan</button>
                @endif

                <options-dropdown>
                    <div v-cloak class="dropdown-content">
                        <a class="dropdown-link" href="{{ route('chief.back.invites.resend', $user->id) }}">Stuur nieuwe uitnodiging</a>

                        @if($user->isEnabled())
                            <button form="disableUserForm" form="disableUserForm" type="submit" class="text-left dropdown-link">
                                {{ ucfirst($user->firstname) }} blokkeren
                            </button>
                        @else
                            <button form="enableUserForm" form="enableUserForm" type="submit" class="text-left dropdown-link">
                                {{ ucfirst($user->firstname) }} deblokkeren
                            </button>
                        @endif
                    </div>
                </options-dropdown>
            </div>
        @endcomponent
    </div>
@endsection

@section('content')
    @if($user->isEnabled())
        <form id="disableUserForm" method="POST" action="{{ route('chief.back.users.disable', $user->id) }}">
            @csrf
            {{-- <p>Om {{ $user->firstname }} tijdelijk de toegang <br>te ontnemen, kan je de account <input type="submit" class="text-error" value="blokkeren">.</p> --}}
        </form>
    @else
        <form id="enableUserForm" method="POST" action="{{ route('chief.back.users.enable', $user->id) }}">
            @csrf
            {{-- <p>{{ $user->firstname }} is momenteel geblokkeerd. <br> <input type="submit" class="text-primary" value="Verleen opnieuw toegang">.</p> --}}
        </form>
    @endif

    <div class="container-sm">
        <div class="row">
            <div class="w-full">
                <div class="window window-white">
                    <form id="updateForm" action="{{ route('chief.back.users.update',$user->id) }}" method="POST" class="mb-0">
                        @csrf
                        @method('put')

                        <div class="space-y-8">
                            @include('chief::admin.users._form')
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
