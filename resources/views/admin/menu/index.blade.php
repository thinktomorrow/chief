@extends('chief::layout.master')

@section('page-title', 'Menu item toevoegen')

@section('header')
    <div class="container-sm">
        @component('chief::layout._partials.header')
            @slot('title', 'Menu overzicht')

            @slot('breadcrumbs')
                <a href="{{ route('chief.back.dashboard') }}" class="link link-primary">
                    <x-icon-label type="back">Dashboard</x-icon-label>
                </a>
            @endslot
        @endcomponent
    </div>
@endsection

@section('content')
    <div class="container-sm">
        <div class="row">
            <div class="w-full">
                <div class="window window-white">
                    <div class="divide-y divide-grey-100 -m-8">
                        @foreach($menus as $menu)
                            <div class="px-8 py-4">
                                <div class="flex justify-between items-center">
                                    <a href="{{ route('chief.back.menus.show', $menu->key()) }}">
                                        <span class="text-lg font-medium text-grey-900">{{ $menu->label() }}</span>
                                    </a>

                                    <a href="{{ route('chief.back.menus.show', $menu->key()) }}" class="link link-primary">
                                        <x-icon-label type="edit"></x-icon-label>
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
