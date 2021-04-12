@extends('chief::back._layouts.master')

@section('page-title', 'Menu ' . $menu->label())

@component('chief::back._layouts._partials.header')
    @slot('title', 'Menu ' . $menu->label())

    @if(Thinktomorrow\Chief\Site\Menu\Menu::all()->count() > 1)
        @slot('breadcrumbs')
            <a href="{{ route('chief.back.menus.index') }}" class="link link-primary">
                <x-link-label type="back">Menu overzicht</x-link-label>
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
                    <p>Momenteel zijn er nog geen menu items toegevoegd.</p>
                </div>
            </div>
        </div>
    @else
        <div class="container">
            <div class="row">
                <div class="w-full lg:w-2/3">
                    <div class="window window-white">
                        <div class="divide-y divide-grey-200 -mx-12 -my-6">
                            @foreach($menuItems as $menuItem)
                                @include('chief::admin.menu._partials.menu-item', [
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
