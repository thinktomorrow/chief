<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Urls\Form;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Site\Urls\ProvidesUrl\ProvidesUrl;

final class LinkForm
{
    private ProvidesUrl $model;
    private Collection $urlRecords;

    private function __construct(ProvidesUrl $model, Collection $urlRecords)
    {
        $this->model = $model;
        $this->urlRecords = $urlRecords;
    }

    public static function fromModel(Model $model): self
    {
        return new static($model, \Thinktomorrow\Chief\Site\Urls\UrlRecord::getByModel($model)
            ->groupBy('locale')
            ->map(function ($records) {
                return $records->sortBy('redirect_id')->sortByDesc('created_at');
            }));
    }

    public function links(): array
    {
        $links = [];

        foreach (config('chief.locales') as $locale) {

            $records = $this->urlRecords->get($locale, collect());
            $currentRecord = $records->reject->isRedirect()->first();

            $links[$locale] = (object)[
                'current'   => $currentRecord,
                'redirects' => $records->filter->isRedirect(),
            ];
        }

        return $links;
    }

    public function formValues(): array
    {
        $values = [];

        foreach (config('chief.locales') as $locale) {

            $currentRecord = $this->urlRecords->get($locale, collect())->reject->isRedirect()->first();

            $values[$locale] = (object)[
                'host'         => $this->model->resolveUrl($locale, $this->model->baseUrlSegment($locale)) . '/',
                'fixedSegment' => $this->model->baseUrlSegment($locale),
                'value'        => $currentRecord
                    ? $this->rawSlugValue($currentRecord->slug, $this->model->baseUrlSegment($locale))
                    : null,
            ];
        }

        return $values;
    }

    public function exist(): bool
    {
        return $this->urlRecords->isNotEmpty();
    }

    private function rawSlugValue(string $slug, string $baseUrlSegment): string
    {
        // If this is a '/' slug, it indicates the homepage for this locale. In this case,
        // we wont be trimming the slash
        if ($slug === '/') {
            return $slug;
        }

        return $this->removeBaseUrlSegment($slug, $baseUrlSegment);
    }

    private function removeBaseUrlSegment(string $slug, string $baseUrlSegment): string
    {
        if ($baseUrlSegment && 0 === strpos($slug, $baseUrlSegment)) {
            return trim(substr($slug, strlen($baseUrlSegment)), '/');
        }

        return $slug;
    }
}
