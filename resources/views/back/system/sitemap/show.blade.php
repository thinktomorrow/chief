@extends('chief::back._layouts.master')

@section('page-title', "Sitemap")

@component('chief::back._layouts._partials.header')
    @slot('title', 'Sitemap')
    @slot('extra', 'De sitemaps worden elke nacht automatisch opgemaakt. Dit gebeurt per taal.')
    <div class="inline-group-s">
        <a @click="generateSitemap('generate-sitemap')" class="">Genereer opnieuw</a>
    </div>
@endcomponent

@section('content')
    <div class="mt-8">

        Na het genereren zal de sitemap op volgende link te vinden zijn:
        @foreach($sitemaps as $sitemap)
            <b>{{$sitemap}}</b>
        @endforeach


        <p class="mt-8 w-2/3">
            <span class="font-bold">Waarom is een sitemap van belang?</span><br>
            Een sitemap laat aan Google weten welke pagina's er allemaal geindexeerd mogen worden.
            Het genereren gaat iedere pagina af en voegt elke link toe aan de sitemap. Dus pagina's die nergens op een pagina gelinkt zijn zullen niet in de sitemap terecht komen.
            <br>
            <a target="_blank" rel="noopener" href="https://support.google.com/webmasters/answer/156184?hl=nl">Meer lezen over Google sitemaps</a>
        </p>

    </div>

    <modal id="generate-sitemap" class="large-modal" :active="false" title=''>
        Bezig met het maken van de sitemaps
    </modal>
@stop
