<x-chief::page.template title="Sitemap">
    <x-slot name="hero">
        <x-chief::page.hero title="Sitemap" class="max-w-3xl">
            <a @click="generateSitemap('generate-sitemap')" title="Vernieuw nu" class="btn btn-primary">
                Vernieuw nu
            </a>
        </x-chief::page.hero>
    </x-slot>

    <x-chief::page.grid class="max-w-3xl">
        <div class="card">
            <div class="prose prose-spacing prose-dark">
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
    </x-chief::page.grid>

    <modal id="generate-sitemap" title="Sitemap genereren ..." size="large" :active="false">
        <div class="prose prose-spacing prose-dark">
            Bezig met het genereren van een sitemap. Dit kan eventjes duren.
        </div>
    </modal>
</x-chief::page.template>
