@extends('chief::back._layouts.master')

@section('page-title','Voeg nieuw menu-item toe')

@component('chief::back._layouts._partials.header')
    @slot('title', 'Menu item toevoegen.')
        <button data-submit-form="createForm" type="button" class="btn btn-primary">Toevoegen</button>
    @endcomponent

    @section('content')

        <form id="createForm" method="POST" action="{{ route('chief.back.menuitem.store') }}" enctype="multipart/form-data" role="form">
            {{ csrf_field() }}

            @include('chief::admin.menu._form')

        </form>

    @stop
