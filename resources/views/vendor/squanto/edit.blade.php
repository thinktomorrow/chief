@extends('chief::back._layouts.master')

@section('page-title')
    {{ $page->label }}
@stop

@component('chief::back._layouts._partials.header')
    @slot('title', $page->label)
    @slot('subtitle')
        <a class="center-y" href="{{ route('squanto.index') }}">
            <svg width="18" height="18" class="mr-2"><use xlink:href="#arrow-left"/></svg>
        </a>
    @endslot

    @can('create-squanto')
        <a href="{{ route('squanto.lines.create', $page->id) }}" class="btn btn-primary inline-flex items-center">
            <svg width="18" height="18" class="mr-2"><use xlink:href="#add"/></svg>
            <span>Nieuwe vertaling toevoegen</span>
        </a>
    @endcan
    <button data-submit-form="translationForm" class="btn btn-primary"><i class="fa fa-check"></i>Wijzigingen opslaan</button>
@endcomponent

@section('content')
    <form id="translationForm" method="POST" action="{{ route('squanto.update',$page->id) }}" role="form">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PUT">

        @include('squanto::_formtabs')

        <!-- hide form button but keep it so input enters still work for submission of form -->
        <button class="btn btn-primary hidden" type="submit"><i class="fa fa-check"></i>Wijzigingen opslaan</button>

    </form>


@stop

@push('custom-scripts-after-vue')
    @include('squanto::editor-script')
@endpush
