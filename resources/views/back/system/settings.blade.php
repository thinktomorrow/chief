@extends('back._layouts.master')

@section('page-title', 'settings')

@component('back._layouts._partials.header')
    @slot('title', 'Settings')
@endcomponent

@section('content')
    <tabs>
        <tab name="Algemeen">
            @include('back.system._partials.general')
        </tab>
        <tab name="Bedrijfsgegevens">
            @include('back.system._partials.company')
        </tab>
        <tab name="Social">
            @include('back.system._partials.social')
        </tab>
        <tab name="Social cards">
            @include('back.system._partials.socialcards')
        </tab>
        <tab name="E-mail">
            @include('back.system._partials.mail')
        </tab>
        <tab name="Analytics">
            @include('back.system._partials.analytics')
        </tab>
        <tab name="SEO">
            @include('back.system._partials.seo')
        </tab>
    </tabs>

@stop
