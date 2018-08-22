@extends('chief::back._layouts.master')

@section('page-title','Voeg nieuw menu-item toe')

@component('chief::back._layouts._partials.header')
    @slot('title', 'Menu overzicht')
@endcomponent

@section('content')
    <div class="stack-l">
        @foreach($menus as $menu)

            <div class="row bg-white inset panel panel-default stack">
                <div class="column-9">
                    <a class="text-black bold" href="{{ route('chief.back.menus.show', $menu->key()) }}">
                        {{ $menu->label() }}
                    </a>
                </div>
                <div class="column-3 text-right">
                    <options-dropdown class="inline-block">
                        <div class="inset-s" v-cloak>
                            <a href="{{ route('chief.back.menus.show', $menu->key()) }}" class="block squished-s --link-with-bg">Beheren</a>
                        </div>
                    </options-dropdown>
                </div>
            </div>
        @endforeach
    </div>

@stop
