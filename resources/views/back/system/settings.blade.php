@extends('chief::back._layouts.master')

@section('page-title', 'settings')

@component('chief.back._layouts._partials.header')
    @slot('title', 'Settings')
@endcomponent

@section('content')
    <tabs>
        <tab name="Algemeen">
            @include('chief::back.system._partials.general')
        </tab>
        <tab name="Bedrijfsgegevens">
            @include('chief::back.system._partials.company')
        </tab>
        <tab name="E-mail">
            @include('chief::back.system._partials.mail')
        </tab>
        <tab name="Social">
            @include('chief::back.system._partials.social')
        </tab>
        <tab name="Social cards">
            @include('chief::back.system._partials.socialcards')
        </tab>
        <tab name="SEO">
            @include('chief::back.system._partials.seo')
        </tab>
        <tab name="Analytics">
            @include('chief::back.system._partials.analytics')
        </tab>
    </tabs>

@stop
