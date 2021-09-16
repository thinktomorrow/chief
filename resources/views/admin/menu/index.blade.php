@extends('chief::layout.master')

@section('page-title', 'Menu item toevoegen')

@section('header')
    <div class="container-sm">
        @component('chief::layout._partials.header')
            @slot('title', 'Menu overzicht')

            @slot('breadcrumbs')
                <a href="{{ route('chief.back.dashboard') }}" class="link link-primary">
                    <x-chief-icon-label type="back">Dashboard</x-chief-icon-label>
                </a>
            @endslot
        @endcomponent
    </div>
@endsection

@section('content')
    <div class="container-sm">
        <div class="row">
            <div class="w-full">
                <div class="window window-white window-xs">
                    <div class="-window-xs divide-y divide-grey-100">
                        @foreach($menus as $menu)
                            <div class="window-xs-y window-sm-x">
                                <div class="flex items-center justify-between">
                                    <a href="{{ route('chief.back.menus.show', $menu->key()) }}">
                                        <span class="text-lg font-semibold text-grey-900">{{ $menu->label() }}</span>
                                    </a>

                                    <a href="{{ route('chief.back.menus.show', $menu->key()) }}" class="link link-primary -mt-0.5">
                                        <x-chief-icon-label type="edit"></x-chief-icon-label>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
