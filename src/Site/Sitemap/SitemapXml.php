<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Sitemap;

use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Collection;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;

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
        foreach ($this->urls as $url) {
            $urlTag = Url::create($url);

            if (isset($this->alternateUrls[$url])) {
                foreach ($this->alternateUrls[$url] as $locale => $alternateUrl) {
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

        $this->urls = $models
            ->reject(function (Visitable $model) use ($locale) {
                // In case the url is not found or present for given locale.
                return ! $model->url($locale);
            })
            ->map(function (Visitable $model) use ($locale, $alternateLocales) {
                $url = $model->url($locale);

                $alternateUrls = [];

                foreach ($alternateLocales as $alternateLocale) {
                    if ($alternateUrl = $model->url($alternateLocale)) {
                        $alternateUrls[$alternateLocale] = $alternateUrl;
                    }
                }

                $this->alternateUrls[$url] = $alternateUrls;

                return $url;
            });
    }

    private function rejectNonVisitableUrls(): void
    {
        $pool = new Pool($this->httpClient, $this->crawlableUrlGenerator(), [
            'concurrency' => 5,
            'fulfilled' => function (Response $response, $index) {
                if ($response->getStatusCode() !== \Symfony\Component\HttpFoundation\Response::HTTP_OK) {
                    unset($this->urls[$index]);
                }
            },
            'rejected' => function ($_reason, $index) {
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
        foreach ($this->urls as $index => $url) {
            yield $index => new Request('GET', $url);
        }
    }
}
