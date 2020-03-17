@extends('chief::back._layouts.master')

@section('page-title', "Sitemap")

@component('chief::back._layouts._partials.header')
    @slot('title', 'Sitemap')
    @slot('extra', 'De sitemaps worden elke nacht automatisch opgemaakt. Dit gebeurt per taal.')
    <div class="inline-group-s">
        <a class="cursor-pointer" @click="generateSitemap('generate-sitemap')" class="">Vernieuw nu</a>
    </div>
@endcomponent

@section('content')
    <div class="mt-8">

        <div>
            @foreach($sitemapFiles as $sitemapFile)
                <div class="bg-white panel inset-s stack-s flex items-center">
                    <div>
                        <span class="font-bold">{{ $sitemapFile->getFileName() }}</span>
                        <a href="{{ url($sitemapFile->getFileName()) }}" download class="block">{{ url($sitemapFile->getFileName()) }}</a>
                    </div>
                    <span class="text-success cursor-pointer" style="margin-left: auto; float:right; padding:2px 5px;">
                        {{ \Carbon\Carbon::createFromTimestamp($sitemapFile->getMTime())->diffForHumans() }} vernieuwd
                    </span>
                </div>
            @endforeach
        </div>

        <p class="mt-8">De sitemaps worden automatisch gebruikt door de zoekpagina's zoals Google en Bing. U kan ook de links toevoegen aan uw <a target="_blank" rel="noopener" href="https://search.google.com/search-console">search console</a>.</p>

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
