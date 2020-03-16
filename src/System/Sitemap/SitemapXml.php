<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\System\Sitemap;

use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use Spatie\Sitemap\Sitemap;
use GuzzleHttp\Psr7\Request;
use Spatie\Sitemap\Tags\Url;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Urls\UrlRecord;
use GuzzleHttp\Exception\RequestException;
use Thinktomorrow\Chief\Urls\ProvidesUrl\ProvidesUrl;

class SitemapXml
{
    /** @var Sitemap */
    private $sitemap;

    /** @var Client */
    private $httpClient;

    /** @var Collection */
    private $urls;

    /** @var array */
    private $alternateUrls;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;

        $this->reset();
    }

    private function reset(): void
    {
        $this->sitemap = new Sitemap();
        $this->urls = collect();
        $this->alternateUrls = [];
    }

    public function generate(string $locale, array $alternateLocales = []): string
    {
        $this->reset();

        $this->prepareOnlineUrls($locale, $alternateLocales);

        $this->rejectNonVisitableUrls($locale);

        return $this->generateXml();
    }

    private function generateXml(): string
    {
        foreach($this->urls as $url) {
            $urlTag = Url::create($url);

            if(isset($this->alternateUrls[$url])) {
                foreach($this->alternateUrls[$url] as $locale => $alternateUrl) {
                    $urlTag->addAlternate($alternateUrl, $locale);
                }
            }

            $this->sitemap->add($urlTag);
        }

        return $this->sitemap->render();
    }

    private function prepareOnlineUrls(string $locale, array $alternateLocales = []): void
    {
        $models = UrlRecord::allOnlineModels($locale);

        $this->urls = $models->map(function(ProvidesUrl $model) use($locale, $alternateLocales){
            $url = $model->url($locale);
            $alternateUrls = [];

            foreach($alternateLocales as $alternateLocale) {
                $alternateUrls[$alternateLocale] = $model->url($alternateLocale);
            }

            $this->alternateUrls[$url] = $alternateUrls;

            return $url;
        });
    }

    private function rejectNonVisitableUrls()
    {
        $pool = new Pool($this->httpClient, $this->crawlableUrlGenerator(), [
            'concurrency' => 5,
            'fulfilled' => function (Response $response, $index) {
                if($response->getStatusCode() !== \Symfony\Component\HttpFoundation\Response::HTTP_OK){
                    unset($this->urls[$index]);
                }
            },
            'rejected' => function (RequestException $reason, $index) {
                unset($this->urls[$index]);
            },
        ]);

        // Initiate the transfers and create a promise
        $promise = $pool->promise();

        // Force the pool of requests to complete.
        $promise->wait();
    }

    private function crawlableUrlGenerator(): \Generator
    {
        foreach($this->urls as $index => $url){
            yield $index => new Request('GET', $url);
        }
    }
}
