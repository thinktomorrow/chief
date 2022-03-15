@extends('chief::layout.master')

@section('page-title', "Sitemap")

@section('header')
    <div class="container-sm">
        @component('chief::layout._partials.header')
            @slot('title', 'Sitemap')

            @slot('breadcrumbs')
                <a href="{{ route('chief.back.dashboard') }}" class="link link-primary">
                    <x-chief-icon-label type="back">Dashboard</x-chief-icon-label>
                </a>
            @endslot

            <a @click="generateSitemap('generate-sitemap')" class="btn btn-primary">Vernieuw nu</a>
        @endcomponent
    </div>
@endsection

@section('content')
    <div class="container-sm">
        <div class="row">
            <div class="w-full">
                <div class="window">
                    <div class="prose prose-dark">
                        <p>De sitemaps worden elke nacht automatisch opgemaakt. Dit gebeurt per taal.</p>

                        <div class="my-6 space-y-2">
                            @foreach($sitemapFiles as $sitemapFile)
                                <div class="flex items-center justify-between">
                                    <div class="flex flex-col space-y-1">
                                        <span class="font-bold">{{ $sitemapFile->getFileName() }}</span>

                                        <a
                                            href="{{ url($sitemapFile->getFileName()) }}"
                                            title="Download sitemap"
                                            class="link link-primary"
                                            download
                                        >
                                            {{ url($sitemapFile->getFileName()) }}
                                        </a>
                                    </div>

                                    <span class="text-grey-500">
                                        {{ \Carbon\Carbon::createFromTimestamp($sitemapFile->getMTime())->diffForHumans() }}
                                        vernieuwd
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <p>
                            De sitemaps worden automatisch gebruikt door de zoekpagina's zoals Google en Bing.
                            U kan ook de links toevoegen aan jouw
                            <a
                                href="https://search.google.com/search-console"
                                title="Google search console"
                                target="_blank"
                                rel="noopener"
                                class="link link-primary"
                            > search console </a>.
                        </p>

                        <h3>Waarom is een sitemap van belang?</h3>

                        <p>
                            Een sitemap laat aan Google weten welke pagina's er allemaal geindexeerd mogen worden.
                            Het genereren gaat iedere pagina af en voegt elke link toe aan de sitemap.
                            Dus pagina's die nergens op een pagina gelinkt zijn zullen niet in de sitemap terecht komen.
                        </p>

                        <p>
                            <a
                                href="https://support.google.com/webmasters/answer/156184?hl=nl"
                                title="Meer lezen over Google sitemaps"
                                target="_blank"
                                rel="noopener"
                                class="link link-primary"
                            >
                                Meer lezen over Google sitemaps
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <modal id="generate-sitemap" title="Sitemap genereren ..." size="large" :active="false">
        <div class="prose prose-dark">
            Bezig met het genereren van een sitemap. Dit kan eventjes duren.
        </div>
    </modal>
@stop
