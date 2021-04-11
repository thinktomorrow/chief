@extends('chief::back._layouts.master')

@section('page-title','Voeg nieuw menu-item toe')

@component('chief::back._layouts._partials.header')
    @slot('title', 'Menu item toevoegen')

    @slot('breadcrumbs')
        {{-- TODO: use correct route --}}
        <a href="/admin/menus" class="link link-primary">
            <x-link-label type="back">Ga terug</x-link-label>
        </a>
    @endslot

    <button data-submit-form="createForm" type="button" class="btn btn-primary">Toevoegen</button>
@endcomponent

@section('content')
    <div class="container">
        <div class="row">
            <div class="w-full lg:w-2/3">
                <div class="window window-white">
                    <form
                        id="createForm"
                        method="POST"
                        action="{{ route('chief.back.menuitem.store') }}"
                        enctype="multipart/form-data"
                        role="form"
                    >
                        {{ csrf_field() }}

                        @include('chief::admin.menu._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
