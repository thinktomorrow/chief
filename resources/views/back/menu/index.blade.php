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
    <div class="treeview stack-l">
        <div class="row">
            <div class="column center-y">
                <strong>Label</strong>
            </div>
            <div class="column-4 center-y">
                <strong>Link</strong>
            </div>
            <div class="column-2">

            </div>
        </div>
        @foreach($menu as $menuitem)

            <hr class="separator">

            @include('chief::back.menu._partials._rowitem')

            @foreach($menuitem->children as $child)

                @include('chief::back.menu._partials._rowitem', ['level' => 1])

                @foreach($child->children as $subchild)
                    @include('chief::back.menu._partials._rowitem', ['level' => 2])
                @endforeach

                <div class="stack"></div>

            @endforeach
        @endforeach
    </div>

@stop
