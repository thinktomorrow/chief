<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Sitemap;

use Illuminate\Support\Collection;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Thinktomorrow\AssetLibrary\AssetContract;
use Thinktomorrow\Chief\Fragments\App\Repositories\ContextRepository;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentRepository;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Urls\Models\UrlRecord;

class ImageSitemapXml
{
    /** @var Sitemap */
    private $sitemap;

    private ContextRepository $contextRepository;

    private FragmentRepository $fragmentRepository;

    public function __construct(ContextRepository $contextRepository, FragmentRepository $fragmentRepository)
    {
        $this->reset();

        $this->contextRepository = $contextRepository;
        $this->fragmentRepository = $fragmentRepository;
    }

    private function reset(): void
    {
        $this->sitemap = new Sitemap;
    }

    public function generate(string $locale): string
    {
        $this->reset();

        $models = $this->getOnlineModels($locale);

        return $this->generateXml($models, $locale);
    }

    private function generateXml(Collection $models, string $locale): string
    {
        foreach ($models as $model) {
            $urlTag = Url::create($model->url($locale));

            // Get images on model and its fragments...
            $assets = $this->getAssetsFromModel($model, $locale);

            /** @var AssetContract $asset */
            foreach ($assets as $asset) {
                $caption = $asset->getData('alt.'.$locale, $asset->getData('alt', ''));
                if (is_array($caption) || ! $caption) {
                    $caption = '';
                }

                $urlTag->addImage($asset->getUrl(), $caption, '', $asset->getFileName());
            }

            $this->sitemap->add($urlTag);
        }

        return $this->sitemap->render();
    }

    private function getOnlineModels(string $locale): Collection
    {
        return UrlRecord::allOnlineModels($locale)->reject(function (Visitable $model) use ($locale) {
            // In case the url is not found or present for given locale.
            return ! $model->url($locale);
        });
    }

    private function getAssetsFromModel(Visitable $model, string $locale): Collection
    {
        $assets = $model->assets(null, $locale);

        if (! $context = $this->contextRepository->findBySite($model->modelReference(), $locale)) {
            return $assets;
        }

        $fragments = $this->fragmentRepository->getByContext($context->id)->reject(function ($fragment) {
            return $fragment->getFragmentModel()->isOffline();
        });

        foreach ($fragments as $fragment) {
            $assets = $assets->merge($fragment->getFragmentModel()->assets(null, $locale));
        }

        return $assets;
    }
}
