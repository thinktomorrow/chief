@extends('chief::back._layouts.master')

@section('page-title','Voeg nieuw menu-item toe')

@component('chief::back._layouts._partials.header')
    @slot('title', 'Een menu item toevoegen.')
    <button data-submit-form="createForm" type="button" class="btn btn-primary">Toevoegen</button>
@endcomponent

@section('content')
    <ul>
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
    </ul>
@stop