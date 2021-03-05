@extends('chief::back._layouts.master')

@section('page-title','Audit')

@component('chief::back._layouts._partials.header')
    @slot('title', 'Audit.')
@endcomponent

@section('content')
    <div class="stack-l">
        <div class="row px-4">
            <div class="column-3 center-y">
                <strong>Activiteit</strong>
            </div>
            <div class="column-3 center-y">
                <strong>Model</strong>
            </div>
            <div class="column-3 center-y">
                <strong>Gebruiker</strong>
            </div>
            <div class="column-3 center-y">
                <strong>Tijd</strong>
            </div>
        </div>
        <section class="bg-white border border-grey-100 rounded inset-s stack-s">
            @if($activity->isEmpty())
                Er zijn nog geen aanpassingen gebeurt in de applicatie.
            @else
                @foreach($activity as $event)
                    <div class="row border-b border-grey-100 py-2">
                        <div class="column-3 center-y">
                            {{ $event->description }}
                        </div>
                        <div class="column-3 center-y">
                            {{ $event->subject_type }}
                        </div>
                        <div class="column-3 center-y">
                            @if($event->causer)
                                <a href="{{route('chief.back.audit.show', $event->causer_id)}}">{{ $event->causer->fullname }}</a>
                            @else
                                /
                            @endif
                        </div>
                        <div class="column-3 center-y">
                            {{ $event->created_at }}
                        </div>
                    </div>
                @endforeach
            @endif
        <section>
    </div>

@stop
