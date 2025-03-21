@php
    use Carbon\Carbon;
@endphp

<x-chief::page.template title="Sitemap" container="md">
    <x-chief::window class="card">
        <div class="prose prose-dark prose-spacing">
            <p>De sitemaps worden elke nacht automatisch opgemaakt. Dit gebeurt per taal.</p>

            <div class="my-6 space-y-2">
                @foreach ($sitemapFiles as $sitemapFile)
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
                            {{ Carbon::createFromTimestamp($sitemapFile->getMTime())->diffForHumans() }}
                            vernieuwd
                        </span>
                    </div>
                @endforeach
            </div>

            <p>
                De sitemaps worden automatisch gebruikt door de zoekpagina's zoals Google en Bing. U kan ook de links
                toevoegen aan jouw
                <a
                    href="https://search.google.com/search-console"
                    title="Google search console"
                    target="_blank"
                    rel="noopener"
                    class="link link-primary"
                >
                    search console
                </a>
                .
            </p>

            <div x-data="{ isLoading: null, status: null }" class="my-6">
                <x-chief::button
                    variant="grey"
                    x-on:click="
                        () => {
                            isLoading = true
                            status = null
                            window.axios
                                .post('{{ route('chief.back.sitemap.generate') }}', {
                                    _method: 'POST',
                                })
                                .then((response) => {
                                    isLoading = false
                                    status = 'success'

                                    window.location.reload()
                                })
                                .catch((errors) => {
                                    isLoading = false
                                    status = 'error'
                                })
                        }
                    "
                >
                    <x-chief::icon.refresh />
                    <span>Vernieuw sitemap nu</span>
                </x-chief::button>

                <p x-show="isLoading" class="mt-4 animate-pulse">
                    Bezig met het genereren van een sitemap. Dit kan eventjes duren...
                </p>

                <p x-show="status === 'success'" class="mt-4">De sitemap is succesvol gegenereerd.</p>

                <p x-show="status === 'error'" class="mt-4">
                    Er ging iets mis bij het genereren van de sitemap. Probeer het later opnieuw.
                </p>
            </div>

            <h3>Waarom is een sitemap van belang?</h3>

            <p>
                Een sitemap laat aan Google weten welke pagina's er allemaal geindexeerd mogen worden. Het genereren
                gaat iedere pagina af en voegt elke link toe aan de sitemap. Dus pagina's die nergens op een pagina
                gelinkt zijn zullen niet in de sitemap terecht komen.
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
    </x-chief::window>
</x-chief::page.template>
