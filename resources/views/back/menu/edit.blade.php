@extends('chief::back._layouts.master')

@section('page-title','Voeg nieuw menu-item toe')

@component('chief::back._layouts._partials.header')
    @slot('title', 'Menu item bewerken.')
    @slot('subtitle')
        <a class="center-y" href="{{ route('chief.back.menu.index') }}"><span class="icon icon-arrow-left"></span> Terug naar het menu overzicht</a>
    @endslot
        <form action="{{route('chief.back.menu.destroy', $menuitem->id)}}" method="POST">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
            <span class="btn btn-link inline-block inline-s" @click="showModal('delete-menuitem-{{$menuitem->id}}')">
                verwijderen
            </span>
        </form>
        <button data-submit-form="updateForm" type="button" class="btn btn-primary">Opslaan</button>
    @endcomponent

    @section('content')

        <form id="updateForm" method="POST" action="{{ route('chief.back.menu.update', $menuitem->id) }}" enctype="multipart/form-data" role="form">
            {{ csrf_field() }}
            {{ method_field('PUT') }}

            @include('chief::back.menu._form')

        </form>

    @stop

@push('custom-components')
    @include('chief::back.menu._partials.delete-modal')
@endpush
