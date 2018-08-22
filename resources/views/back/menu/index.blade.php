@extends('chief::back._layouts.master')

@section('page-title','Voeg nieuw menu-item toe')

@component('chief::back._layouts._partials.header')
    @slot('title', 'Menu overzicht')
    {{-- <div class="inline-group-s">
        <a href="{{ route('chief.back.menu.create') }}" class="btn btn-primary">
            <i class="icon icon-plus"></i>
            Voeg een menu-item toe
        </a>
    </div> --}}
@endcomponent

@section('content')
    <div class="treeview stack-l">
        <div class="row">
            <div class="column center-y">
                <strong>Label</strong>
            </div>
            {{-- <div class="column-4 center-y">
                <strong>Link</strong>
            </div> --}}
            <div class="column-2"></div>
        </div>
        @foreach($menu as $key => $menuitem)

            <div class="row bg-white inset panel panel-default stack">
                <div class="column-9">
                        <a class="text-black bold" href="{{ route('chief.back.menu.show', $key) }}">
                            {{ $menuitem['label'] }}
                        </a>
                        {{-- <div>
                            <span class="text-subtle">Laatst aangepast op {{ $page->updated_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="stack-s font-s">
                            {{ teaser($page->content,250,'...') }}
                        </div> --}}
                </div>
            </div>
        @endforeach
    </div>

@stop
