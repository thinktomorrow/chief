@extends('chief::back._layouts.master')

@section('page-title', "Modules")

@component('chief::back._layouts._partials.header')
    @slot('title', 'Sitemap')
    <div class="inline-group-s">
        <a @click="generateSitemap('generate-sitemap')" class="btn btn-secondary inline-flex items-center">
            <span class="mr-2"><svg width="18" height="18"><use xlink:href="#add"/></svg></span>
            <span>Genereer nieuwe sitemap</span>
        </a>
    </div>
@endcomponent

@section('content')
    Hier kan u de sitemap handmatig genereren.
    Een Sitemap is belangrijk zodat Google een startpunt heeft van welke url's geindexeerd moeten worden.
    Het genereren gaat iedere pagina af en voegt elke link toe aan de sitemap. Dus pagina's die nergens op een pagina gelinkt zijn zullen niet in de sitemap terecht komen.

    Na het genereren zal de sitemap op volgende link te vinden zijn:
    @foreach($sitemaps as $sitemap)
        <b>{{$sitemap}}</b>
    @endforeach

    @include('chief::back.sitemap._partials.generate-sitemap')
@stop
