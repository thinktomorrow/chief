@extends('chief::back._layouts.master')

@section('page-title', 'Menu item toevoegen')

@section('header')
    <div class="container-sm">
        @component('chief::back._layouts._partials.header')
            @slot('title', 'Menu overzicht')
        @endcomponent
    </div>
@endsection

@section('content')
    <div class="container-sm">
        <div class="row">
            <div class="w-full">
                <div class="window window-white">
                    <div class="divide-y divide-grey-200 -m-12">
                        @foreach($menus as $menu)
                            <div class="px-12 py-6">
                                <div class="flex justify-between items-center">
                                    <a href="{{ route('chief.back.menus.show', $menu->key()) }}">
                                        <span class="text-lg font-semibold text-grey-900">{{ $menu->label() }}</span>
                                    </a>

                                    <a href="{{ route('chief.back.menus.show', $menu->key()) }}" class="hover:underline">
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
