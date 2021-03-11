@extends('chief::back._layouts.master')

@section('page-title','Voeg nieuw menu-item toe')

@component('chief::back._layouts._partials.header')
    @slot('title', 'Menu overzicht')
@endcomponent

@section('content')
    <div class="row gutter stack">
        @foreach($menus as $menu)

            <div class="s-column-4 m-column-4 inset-xs">
                <div class="row bg-white border border-grey-100 rounded inset-s center-y">
                    <div class="column">
                        <a class="text-grey-600 bold" href="{{ route('chief.back.menus.show', $menu->key()) }}">
                            {{ $menu->label() }}
                        </a>
                    </div>
                    <div class="column-4 text-right flex flex-col justify-between items-end">
                        <a href="{{ route('chief.back.menus.show', $menu->key()) }}" class="hover:underline">Beheren</a>

                    </div>
                </div>
            </div>
        @endforeach
    </div>

@stop
