@extends('chief::back._layouts.master')

@section('page-title', "Sitemap")

@component('chief::back._layouts._partials.header')
    @slot('title', 'Sitemap')

    @slot('breadcrumbs')
        {{-- TODO: use route --}}
        <a href="/admin" class="link link-primary">
            <x-link-label type="back">Ga terug</x-link-label>
        </a>
    @endslot

    <a @click="generateSitemap('generate-sitemap')" class="btn btn-primary">Vernieuw nu</a>
@endcomponent

@section('content')
    <div class="container">
        <div class="row">
            <div class="w-full lg:w-2/3 prose prose-dark">
                <p>De sitemaps worden elke nacht automatisch opgemaakt. Dit gebeurt per taal.</p>

                <div class="space-y-2 my-6">
                    @foreach($sitemapFiles as $sitemapFile)
                        <div class="bg-white border border-grey-200 rounded-xl p-6 flex justify-between items-center">
                            <div class="flex flex-col space-y-1">
                                <span class="font-bold">{{ $sitemapFile->getFileName() }}</span>

                                <a class="link link-primary" href="{{ url($sitemapFile->getFileName()) }}" download>
                                    {{ url($sitemapFile->getFileName()) }}
                                </a>
                            </div>

                            <span class="text-grey-500">
                                {{ \Carbon\Carbon::createFromTimestamp($sitemapFile->getMTime())->diffForHumans() }} vernieuwd
                            </span>
                        </div>
                    @endforeach
                </div>

                <p>
                    De sitemaps worden automatisch gebruikt door de zoekpagina's zoals Google en Bing.
                    U kan ook de links toevoegen aan jouw <a class="link link-primary" target="_blank" rel="noopener" href="https://search.google.com/search-console">search console</a>.
                </p>

                <h3>Waarom is een sitemap van belang?</h3>

                <p>
                    Een sitemap laat aan Google weten welke pagina's er allemaal geindexeerd mogen worden.
                    Het genereren gaat iedere pagina af en voegt elke link toe aan de sitemap.
                    Dus pagina's die nergens op een pagina gelinkt zijn zullen niet in de sitemap terecht komen.
                </p>

                <p>
                    <a class="link link-primary" target="_blank" rel="noopener" href="https://support.google.com/webmasters/answer/156184?hl=nl">
                        Meer lezen over Google sitemaps
                    </a>
                </p>
            </div>
        </div>
    </div>

    <modal id="generate-sitemap" class="large-modal" :active="false" title=''>
        Bezig met het maken van de sitemaps
    </modal>
@stop
