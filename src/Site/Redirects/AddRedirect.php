<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Redirects;

use Thinktomorrow\Url\Url;
use Thinktomorrow\Url\Root;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Site\Visitable\Visitable;

final class AddRedirect
{
    /**
     * @param string $locale
     * @param string $redirectUrl
     * @param string $targetUrl
     * @return void
     * @throws RedirectUrlAlreadyExists
     * @throws \Thinktomorrow\Chief\Site\Urls\UrlRecordNotFound
     */
    public function handle(string $locale, string $redirectUrl, string $targetUrl): void
    {
        $targetRecord = UrlRecord::findBySlug($targetUrl, $locale);

        $parsedUrl = Url::fromString($redirectUrl);

        // Strip out the slashes and possible host/scheme reference.
        $redirectUrl =
            ($parsedUrl->hasPath() ? $parsedUrl->getPath() : '') .
            ($parsedUrl->hasQuery() ? '?' . $parsedUrl->getQuery() : '') .
            ($parsedUrl->hasHash() ? '#' . $parsedUrl->getHash() : '');

        if(UrlRecord::where('locale', $locale)->where('slug', $redirectUrl)->exists()) {
            throw new RedirectUrlAlreadyExists($redirectUrl . ' [locale: '.$locale.'] already exists as url');
        }

        $redirectRecord = $this->createRecord($targetRecord->model, $locale, $redirectUrl);

        $redirectRecord->redirectTo($targetRecord);
    }

    private function createRecord(Visitable $model, string $locale, string $slug): UrlRecord
    {
        return UrlRecord::create([
            'locale' => $locale,
            'slug' => $slug,
            'model_type' => $model->getMorphClass(),
            'model_id' => $model->id,
        ]);
    }
}
