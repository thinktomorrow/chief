<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\System\Sitemap;

use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use Spatie\Sitemap\Sitemap;
use GuzzleHttp\Psr7\Request;
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

    public function __construct(Sitemap $sitemap, Client $httpClient)
    {
        $this->sitemap = $sitemap;
        $this->httpClient = $httpClient;

        $this->urls = collect();
    }

    public function generate(string $locale): string
    {
        $this->prepareOnlineUrls($locale);

        $this->rejectNonVisitableUrls($locale);

        return $this->generateXml();
    }

    private function generateXml(): string
    {
        foreach($this->urls as $url) {
            $this->sitemap->add($url);
        }

        return $this->sitemap->render();
    }

    private function prepareOnlineUrls(string $locale): void
    {
        $models = UrlRecord::allOnlineModels($locale);

        $this->urls = $models->map(function(ProvidesUrl $model) use($locale){
            return $model->url($locale);
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
            trap($reason);
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
