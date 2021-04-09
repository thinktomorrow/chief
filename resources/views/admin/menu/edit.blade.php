@extends('chief::back._layouts.master')

@section('page-title','Voeg nieuw menu-item toe')

@component('chief::back._layouts._partials.header')
    @slot('title', 'Menu item bewerken')
    @slot('subtitle')
        <div class="inline-block">
            <a class="center-y" href="{{ route('chief.back.menus.index', $menuitem->menu_type) }}">
                <svg width="24" height="24" class="mr-4"><use xlink:href="#arrow-left"/></svg>
                {{-- Terug naar het menu overzicht --}}
            </a>
        </div>
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

                        @include('chief::admin.menu._form')
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('chief::admin.menu._partials.delete-modal')
@stop
