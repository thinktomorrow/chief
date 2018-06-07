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
            <div class="column-2 center-y">
                <strong>Type</strong>
            </div>
        </div>
        <div class="row">
            <div class="column-4 center-y">
                <i class="icon icon-menu inline text-border tree-parent"></i>
                <a href="#">Diensten</a>
            </div>
            <div class="column-3 center-y">
                <a href="#">/diensten</a>
            </div>
            <div class="column-2 center-y">
                <div class="font-s">Collection</div>
            </div>
            <div class="column-3 text-right">
                <a href="#" class="btn btn-link text-font">Aanpassen</a>
                <a href="#"><i class="icon icon-trash icon-fw text-tertiary"></i></a>
            </div>
        </div>
        <div class="row">
            <div class="column-4 center-y indent">
                <i class="icon icon-menu inline text-border tree-parent"></i>
                <a href="#">Hoofddienst 1</a>
            </div>
            <div class="column-3 center-y">
                <a href="#">/hoofddienst-1</a>
            </div>
            <div class="column-2 center-y">
                <div class="font-s">Collection-item</div>
            </div>
            <div class="column-3 text-right">
                <a href="#" class="btn btn-link text-font">Aanpassen</a>
                <a href="#"><i class="icon icon-trash icon-fw text-tertiary"></i></a>
            </div>
        </div>
        <div class="row">
            <div class="column-4 center-y indent-l">
                <i class="icon icon-menu inline text-border"></i>
                <a href="#">Subdienst 1</a>
            </div>
            <div class="column-3 center-y">
                <a href="#">/subdienst-1</a>
            </div>
            <div class="column-2 center-y">
                <div class="font-s">Collection-item</div>
            </div>
            <div class="column-3 text-right">
                <a href="#" class="btn btn-link text-font">Aanpassen</a>
                <a href="#"><i class="icon icon-trash icon-fw text-tertiary"></i></a>
            </div>
        </div>
        <div class="row">
            <div class="column-4 center-y">
                <i class="icon icon-menu inline text-border tree-parent"></i>
                <a href="#">Team</a>
            </div>
            <div class="column-3 center-y">
                <a href="#">/team</a>
            </div>
            <div class="column-2 center-y">
                <div class="font-s">Internal link</div>
            </div>
            <div class="column-3 text-right">
                <a href="#" class="btn btn-link text-font">Aanpassen</a>
                <a href="#"><i class="icon icon-trash icon-fw text-tertiary"></i></a>
            </div>
        </div>
        <div class="row">
            <div class="column-4 center-y indent">
                <i class="icon icon-menu inline text-border"></i>
                <a href="#">Teamlid 1</a>
            </div>
            <div class="column-3 center-y">
                <a href="#">/teamlid-1</a>
            </div>
            <div class="column-2 center-y">
                <div class="font-s">Custom link</div>
            </div>
            <div class="column-3 text-right">
                <a href="#" class="btn btn-link text-font">Aanpassen</a>
                <a href="#"><i class="icon icon-trash icon-fw text-tertiary"></i></a>
            </div>
        </div>
        <div class="row">
            <div class="column-4 center-y indent">
                <i class="icon icon-menu inline text-border"></i>
                <a href="#">Teamlid 2</a>
            </div>
            <div class="column-3 center-y">
                <a href="#">/teamlid-2</a>
            </div>
            <div class="column-2 center-y">
                <div class="font-s">Custom link</div>
            </div>
            <div class="column-3 text-right">
                <a href="#" class="btn btn-link text-font">Aanpassen</a>
                <a href="#"><i class="icon icon-trash icon-fw text-tertiary"></i></a>
            </div>
        </div>
    </div>

@stop
