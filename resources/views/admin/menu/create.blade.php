@extends('chief::layout.master')

@section('page-title', 'Menu item toevoegen')

@section('header')
    <div class="container-sm">
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
    <div class="container-sm">
        <div class="row">
            <div class="w-full">
                <div class="window window-white">
                    <form
                        id="createForm"
                        method="POST"
                        action="{{ route('chief.back.menuitem.store') }}"
                        enctype="multipart/form-data"
                        role="form"
                        class="mb-0"
                    >
                        @csrf

                        @include('chief::admin.menu._partials.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
