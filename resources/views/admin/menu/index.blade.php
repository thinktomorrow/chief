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
                <div class="card">
                    <div class="-my-3 divide-y divide-grey-100">
                        @foreach($menus as $menu)
                            <div class="flex items-center justify-between py-3">
                                <a
                                    href="{{ route('chief.back.menus.show', $menu->key()) }}"
                                    title="{{ ucfirst($menu->label()) }}"
                                    class="font-medium body-dark hover:underline"
                                >
                                    {{ ucfirst($menu->label()) }}
                                </a>

                                <a
                                    href="{{ route('chief.back.menus.show', $menu->key()) }}"
                                    title="{{ ucfirst($menu->label()) }}"
                                    class="shrink-0 link link-primary"
                                >
                                    <x-chief-icon-button icon="icon-edit"/>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
