@extends('chief::back._layouts.master')

@section('page-title','Voeg nieuw menu-item toe')

@component('chief::back._layouts._partials.header')
    @slot('title', 'Menu ' . $menu->label())

    @if(Thinktomorrow\Chief\Menu\Menu::all()->count() > 1)
        @slot('subtitle')
            <a class="center-y" href="{{ route('chief.back.menus.index') }}"><span class="icon icon-arrow-left"></span> Terug naar het menu overzicht</a>
        @endslot
    @endif
    <div class="inline-group-s">
        <a href="{{ route('chief.back.menuitem.create', $menu->key()) }}" class="btn btn-primary">
            <i class="icon icon-plus"></i>
            Voeg een menu-item toe
        </a>
    </div>
@endcomponent

@section('content')
    <div class="treeview stack-l">
        <div class="row">
            <div class="column center-y">
                <strong>Label</strong>
            </div>
            <div class="column-4 center-y">
                <strong>Link</strong>
            </div>
            <div class="column-2"></div>
        </div>
        @foreach($menuItems as $menuItem)

            <hr class="separator stack-s">

            @include('chief::back.menu._partials._rowitem', ['item' => $menuItem])

            <div class="stack-s">

                @foreach($menuItem->children as $child)

                    @include('chief::back.menu._partials._rowitem', ['level' => 1, 'item' => $child])

                    <div class="stack-xs">

                        @foreach($child->children as $subchild)

                            @include('chief::back.menu._partials._rowitem', ['level' => 2, 'item' => $subchild])

                            @foreach($child->children as $subchild)
                                @include('chief::back.menu._partials._rowitem', ['level' => 3, 'item' => $subchild])
                            @endforeach

                        @endforeach

                    </div>

                @endforeach

            </div>

        @endforeach
    </div>

@stop
