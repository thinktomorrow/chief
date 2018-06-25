@extends('chief::back._layouts.master')

@section('page-title','Audit')

@component('chief::back._layouts._partials.header')
    @slot('title', 'Events for "'. $causer->fullname . '"')
@endcomponent

@section('content')
    <div class="treeview stack-l">
        <div class="row">
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
        @foreach($activity as $event)
            <div class="row">
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
    </div>

@stop
