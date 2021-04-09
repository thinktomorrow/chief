@extends('chief::back._layouts.master')

@section('page-title', 'Menu ' . $menu->label())

@component('chief::back._layouts._partials.header')
    @slot('title', 'Menu ' . $menu->label())

    @if(Thinktomorrow\Chief\Site\Menu\Menu::all()->count() > 1)
        @slot('subtitle')
            <a class="center-y" href="{{ route('chief.back.menus.index') }}">
                <svg width="24" height="24" class="mr-4"><use xlink:href="#arrow-left"/></svg>
                {{-- Terug naar het menu overzicht --}}
            </a>
        @endslot
    @endif

    <a href="{{ route('chief.back.menuitem.create', $menu->key()) }}" class="btn btn-primary">
        <x-link-label type="add">Voeg een menu item toe</x-link-label>
    </a>
@endcomponent

@section('content')
    @if($menuItems->isEmpty() )
        <div class="container">
            <div class="row">
                <div class="w-full lg:w-2/3 prose prose-dark">
                    <p> Momenteel zijn er nog geen menu-items toegevoegd. </p>
                </div>
            </div>
        </div>
    @else
        <div class="container">
            <div class="row">
                <div class="w-full lg:w-2/3">
                    <div class="window window-white">
                        <div class="divide-y divide-grey-150 -mx-12 -my-6">
                            @foreach($menuItems as $menuItem)
                                @include('chief::admin.menu._partials._rowitem', [
                                    'item' => $menuItem,
                                    'level' => 0
                                ])
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@stop
