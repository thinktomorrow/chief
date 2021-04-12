@extends('chief::back._layouts.master')

@section('page-title','Voeg nieuw menu-item toe')

@component('chief::back._layouts._partials.header')
    @slot('title', 'Menu overzicht')
@endcomponent

@section('content')
    <div class="container">
        <div class="row">
            <div class="w-full lg:w-2/3">
                <div class="window window-white">
                    <div class="divide-y divide-grey-200 -mx-12 -my-6">
                        @foreach($menus as $menu)
                            <div class="px-12 py-6">
                                <div class="flex justify-between items-center">
                                    <a href="{{ route('chief.back.menus.show', $menu->key()) }}">
                                        <span class="text-lg font-semibold text-grey-900">{{ $menu->label() }}</span>
                                    </a>

                                    <a href="{{ route('chief.back.menus.show', $menu->key()) }}" class="hover:underline">
                                        <x-link-label type="edit"></x-link-label>
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
