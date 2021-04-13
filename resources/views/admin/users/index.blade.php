@extends('chief::back._layouts.master')

@section('page-title', 'Admins')

@chiefheader
    @slot('title', 'Admins')
    <a href="{{ route('chief.back.users.create') }}" class="btn btn-secondary">+ Nodig een nieuwe admin uit.</a>
@endchiefheader

@section('content')
    <div class="row gutter stack">
        @foreach($users as $user)
            <div class="s-column-6 m-column-4 inset-xs">
                <div class="row bg-white inset-s border border-grey-100 rounded" style="height:100%">
                    <div>
                        @if(!chiefAdmin()->can('update-user') || ($user->hasRole('developer') && !chiefAdmin()->hasRole('developer')) )
                            <span>{{ $user->fullname }}</span>
                        @else
                            <a href="{{ route('chief.back.users.edit', $user->id) }}">{{ $user->fullname }}</a>
                        @endif

                        <div class="inline-block font-s ml-2">
                            {{-- @if(!$user->isEnabled())
                                <div class="label label-error">geblokkeerd</div>
                            @endif --}}
                            {!! $user->present()->enabledAsLabel() !!}
                        </div>

                        <div class="font-s text-grey-300">
                            <?= implode(', ', $user->roleNames()) ?>
                        </div>
                    </div>

                    @if(chiefAdmin()->can('update-user') && (!$user->hasRole('developer') || chiefAdmin()->hasRole('developer')) )
                        <options-dropdown>
                            <div v-cloak class="dropdown-content">
                                <a class="dropdown-link" href="{{ route('chief.back.users.edit', $user->id) }}">Aanpassen</a>
                            </div>
                        </options-dropdown>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div class="stack inset-xs text-center">
        <a href="{{ route('chief.back.users.create') }}" class="btn btn-secondary">+ Nodig een nieuwe admin uit.</a>
    </div>
@endsection