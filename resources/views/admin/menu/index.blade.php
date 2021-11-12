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
                <x-chief::window>
                    <div class="-my-4 divide-y divide-grey-100">
                        @foreach($menus as $menu)
                            <a
                                href="{{ route('chief.back.menus.show', $menu->key()) }}"
                                title="{{ ucfirst($menu->label()) }}"
                                class="block py-4 text-lg display-base display-dark"
                            >
                                {{ ucfirst($menu->label()) }}
                            </a>
                        @endforeach
                    </div>
                </x-chief::window>
            </div>
        </div>
    </div>
@stop
