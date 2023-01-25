@extends('chief::layout.master')

@section('page-title', 'Menu item toevoegen')

@section('header')
    <div class="container max-w-3xl">
        @component('chief::layout._partials.header')
            @slot('title', 'Menu item toevoegen')

            @slot('breadcrumbs')
                {{-- TODO: use correct route --}}
                <a href="/admin/menus" class="link link-primary">
                    <x-chief-icon-label type="back">Ga terug</x-chief-icon-label>
                </a>
            @endslot

            <button form="createForm" type="submit" class="btn btn-primary">Toevoegen</button>
        @endcomponent
    </div>
@endsection

@section('content')
    <div class="container max-w-3xl">
        <div class="row-start-start">
            <div class="w-full">
                <div class="card">
                    <form
                        id="createForm"
                        method="POST"
                        action="{{ route('chief.back.menuitem.store') }}"
                        enctype="multipart/form-data"
                        role="form"
                    >
                        @csrf

                        <input type="hidden" name="menu_type" value="{{ $menuitem->menu_type }}">

                        @include('chief::admin.menu._partials.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
