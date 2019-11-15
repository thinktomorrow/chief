@extends('chief::back._layouts.master')

@section('page-title','Audit')

@component('chief::back._layouts._partials.header')
    @slot('title', 'Events for "'. $causer->fullname . '"')
    @slot('subtitle')
    <a class="center-y" href="{{ route('chief.back.audit.index') }}">
        <svg width="24" height="24" class="mr-4"><use xlink:href="#arrow-left"/></svg>
        {{-- Terug naar het menu overzicht --}}
    </a>
@endslot
@endcomponent

@section('content')
    <div class="stack-l">
        <div class="row px-4">
            <div class="column-3 center-y">
                <strong>Activity</strong>
            </div>
            <div class="column-3 center-y">
                <strong>Model</strong>
            </div>
            <div class="column-3 center-y">
                <strong>Timestamp</strong>
            </div>
        </div>
        <section class="bg-white border border-grey-100 rounded inset-s stack-s" style="height:100%;">
        @foreach($activity as $event)
            <div class="row border-b border-grey-100 py-2">
                <div class="column-3 center-y">
                    {{ $event->description }}
                </div>
                <div class="column-3 center-y">
                    {{ $event->subject_type }}
                </div>
                <div class="column-3 center-y">
                    {{ $event->created_at }}
                </div>
            </div>
        @endforeach
        </section>
    </div>

@stop
