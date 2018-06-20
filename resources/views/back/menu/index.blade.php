@extends('chief::back._layouts.master')

@section('page-title','Voeg nieuw menu-item toe')

@component('chief::back._layouts._partials.header')
    @slot('title', 'Menubeheer.')
    {{-- <button data-submit-form="createForm" type="button" class="btn btn-primary">Menu-item toevoegen</button> --}}
    <div class="inline-group-s">
        <a href="{{ route('chief.back.menu.create') }}" class="btn btn-primary">
            <i class="icon icon-plus"></i>
            Voeg een menu-item toe
        </a>
    </div>
@endcomponent

@section('content')
    {{-- <ul>
        @foreach($menu as $menuitem)
            <li>{{ $menuitem->label }}
                <ul>
                    @foreach($menuitem->children as $child)
                        <li>{{ $child->label }}
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul> --}}
    <div class="treeview stack-l">
        <div class="row">
            <div class="column-4 center-y">
                <strong>Menu titel</strong>
            </div>
            <div class="column-3 center-y">
                <strong>URL</strong>
            </div>
        </div>
        @foreach($menu as $menuitem)
            <div class="row">
                <div class="column-4 center-y">
                    <i class="icon icon-menu inline text-border tree-parent"></i>
                    <a href="{{ route('chief.back.menu.edit', $menuitem->id) }}">{{ $menuitem->label }}</a>
                </div>
                <div class="column-3 center-y">
                    <a href="#">/diensten</a>
                </div>
                <div class="column-2 center-y">
                <div class="font-s"></div>
                </div>
                <div class="column-3 text-right">
                    <a href="{{ route('chief.back.menu.edit', $menuitem->id) }}" class="btn btn-link text-font">Aanpassen</a>
                    <form action="{{route('chief.back.menu.destroy', $menuitem->id)}}" method="POST">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <button type="submit"><i class="icon icon-trash icon-fw text-tertiary"></i></button>
                    </form>
                </div>
            </div>
            @foreach($menuitem->children as $child)
                <div class="row">
                    <div class="column-4 center-y indent">
                        <i class="icon icon-menu inline text-border tree-parent"></i>
                        <a href="{{ route('chief.back.menu.edit', $child->id) }}">{{ $child->label }}</a>
                    </div>
                    <div class="column-3 center-y">
                        <a href="#">/diensten</a>
                    </div>
                    <div class="column-3 text-right">
                        <a href="{{ route('chief.back.menu.edit', $child->id) }}" class="btn btn-link text-font">Aanpassen</a>
                        <form action="{{route('chief.back.menu.destroy', $child->id)}}" method="POST">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit"><i class="icon icon-trash icon-fw text-tertiary"></i></button>
                        </form>
                    </div>
                </div>
                @foreach($child->children as $subchild)
                    <div class="row">
                        <div class="column-4 center-y indent-l">
                            <i class="icon icon-menu inline text-border tree-parent"></i>
                            <a href="{{ route('chief.back.menu.edit', $subchild->id) }}">{{ $subchild->label }}</a>
                        </div>
                        <div class="column-3 center-y">
                            <a href="#">/diensten</a>
                        </div>
                        <div class="column-2 center-y">
                            <div class="font-s">Collection</div>
                        </div>
                        <div class="column-3 text-right">
                            <a href="{{ route('chief.back.menu.edit', $subchild->id) }}" class="btn btn-link text-font">Aanpassen</a>
                            <form action="{{route('chief.back.menu.destroy', $subchild->id)}}" method="POST">
                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                                <button type="submit"><i class="icon icon-trash icon-fw text-tertiary"></i></button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @endforeach
        @endforeach
    </div>

@stop
