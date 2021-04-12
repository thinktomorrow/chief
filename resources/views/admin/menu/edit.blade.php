@extends('chief::back._layouts.master')

@section('page-title','Voeg nieuw menu-item toe')

@component('chief::back._layouts._partials.header')
    @slot('title', 'Menu item bewerken')

    @slot('breadcrumbs')
        <a href="{{ route('chief.back.menus.index', $menuitem->menu_type) }}" class="link link-primary">
            <x-link-label type="back">Ga terug</x-link-label>
        </a>
    @endslot

    <div class="space-x-3">
        <span class="btn btn-error" @click="showModal('delete-menuitem-{{$menuitem->id}}')">
            Verwijderen
        </span>

        <button data-submit-form="updateForm" type="button" class="btn btn-primary">Opslaan</button>
    </div>
@endcomponent

@section('content')
    <div class="container">
        <div class="row">
            <div class="w-full lg:w-2/3">
                <div class="window window-white">
                    <form
                        id="updateForm"
                        method="POST"
                        action="{{ route('chief.back.menuitem.update', $menuitem->id) }}"
                        enctype="multipart/form-data"
                        role="form"
                    >
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}

                        @include('chief::admin.menu._partials.form')
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('chief::admin.menu._partials.delete-modal')
@stop
