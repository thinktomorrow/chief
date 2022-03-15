@extends('chief::layout.master')

@section('page-title', 'Menu item bewerken')

@section('header')
    <div class="container-sm">
        @component('chief::layout._partials.header')
            @slot('title', 'Menu item bewerken')

            @slot('breadcrumbs')
                <a href="{{ route('chief.back.menus.index', $menuitem->menu_type) }}" class="link link-primary">
                    <x-chief-icon-label type="back">Ga terug</x-chief-icon-label>
                </a>
            @endslot

            <div class="space-x-3">
                <span class="btn btn-error-outline" @click="showModal('delete-menuitem-{{$menuitem->id}}')">
                    Verwijderen
                </span>

                <button form="updateForm" type="submit" class="btn btn-primary">Opslaan</button>
            </div>
        @endcomponent
    </div>
@endsection

@section('content')
    <div class="container-sm">
        <div class="row">
            <div class="w-full">
                <x-chief-forms::window>
                    <form
                        id="updateForm"
                        method="POST"
                        action="{{ route('chief.back.menuitem.update', $menuitem->id) }}"
                        enctype="multipart/form-data"
                        role="form"
                    >
                        @csrf
                        @method('put')

                        @include('chief::admin.menu._partials.form')
                    </form>
                </x-chief-forms::window>
            </div>
        </div>
    </div>

    @include('chief::admin.menu._partials.delete-modal')
@stop
