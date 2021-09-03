@extends('chief::layout.master')

@section('page-title', 'Menu ' . $menu->label())

@section('header')
    <div class="container-sm">
        @component('chief::layout._partials.header')
            @slot('title', 'Menu ' . $menu->label())

            @if(Thinktomorrow\Chief\Site\Menu\Menu::all()->count() > 1)
                @slot('breadcrumbs')
                    <a href="{{ route('chief.back.menus.index') }}" class="link link-primary">
                        <x-chief-icon-label type="back">Menu overzicht</x-chief-icon-label>
                    </a>
                @endslot
            @endif

            <a href="{{ route('chief.back.menuitem.create', $menu->key()) }}" class="btn btn-primary">
                <x-chief-icon-label type="add">Menu item toevoegen</x-chief-icon-label>
            </a>
        @endcomponent
    </div>
@endsection

@section('content')
    <div class="container-sm">
        <div class="row">
            @if($menuItems->isEmpty() )
                <div class="w-full prose prose-dark">
                    <p>Momenteel zijn er nog geen menu items toegevoegd.</p>
                </div>
            @else
                <div class="w-full">
                    <div class="window window-white">
                        <div class="-m-8 divide-y divide-grey-100">
                            @foreach($menuItems as $menuItem)
                                @include('chief::admin.menu._partials.menu-item', [
                                    'item' => $menuItem,
                                    'level' => 0
                                ])
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@stop
