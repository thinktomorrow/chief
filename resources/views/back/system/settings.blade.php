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
            // Content of third tabpanel...
        </tab>
        <tab name="Social cards">
            // Content of third tabpanel...
        </tab>
        <tab name="E-mail">
            // Content of third tabpanel...
        </tab>
        <tab name="Analytics">
            // Content of third tabpanel...
        </tab>
        <tab name="SEO">
            // Content of third tabpanel...
        </tab>
    </tabs>

@stop
