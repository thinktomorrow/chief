<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Urls\Field;

use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\Site\Urls\MemoizedUrlRecord;
use Thinktomorrow\Chief\Site\Urls\ProvidesUrl\ProvidesUrl;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;

class UrlSlugFields extends Fields
{
    final public static function fromModel(ProvidesUrl $model): self
    {
        $fields = self::initEmptyFields(config('chief.locales', []), $model);

        self::fillWithExistingValues($model, $fields);

        return $fields;
    }

    /**
     * @return static
     */
    public static function redirectsFromModel(ProvidesUrl $model): self
    {
        $records = MemoizedUrlRecord::getByModel($model)->reject(function ($record) {
            return ! $record->isRedirect();
        })->sortByDesc('created_at');

        $fields = new static([]);

        foreach ($records as $record) {
            $key = 'redirects-' . $record->locale . '-' . $record->slug;
            $fields[$key] = UrlSlugField::make($key)
                ->setUrlRecord($record)
                ->setFullUrl($model->resolveUrl($record->locale, $record->slug));
        }

        return $fields;
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function toArray(): array
    {
        $array = [];

        foreach ($this->all() as $field) {
            $array[] = $field->toArray();
        }

        return $array;
    }

    /**
     * @param array $locales
     * @param ProvidesUrl $model
     * @return UrlSlugFields
     */
    private static function initEmptyFields(array $locales, ProvidesUrl $model): self
    {
        $fields = new static([]);

        foreach ($locales as $locale) {
            $fields['url-slugs.' . $locale] = UrlSlugField::make('url-slugs.' . $locale)
                ->setBaseUrlSegment($model->baseUrlSegment($locale))
                ->prepend($model->resolveUrl($locale, $model->baseUrlSegment($locale)) . '/')
                ->name('url-slugs[' . $locale . ']')
                ->label($locale);
        }

        return $fields;
    }

    /**
     * @param ProvidesUrl $model
     * @param $fields
     */
    private static function fillWithExistingValues(ProvidesUrl $model, self $fields): void
    {
        $records = UrlRecord::getByModel($model)->reject(function ($record) {
            return $record->isRedirect();
        })->sortBy('locale');

        foreach ($records as $record) {
            if (! isset($fields['url-slugs.' . $record->locale])) {
                continue;
            }

            $fields['url-slugs.' . $record->locale]
                ->setUrlRecord($record)
                ->setBaseUrlSegment($model->baseUrlSegment($record->locale))
                ->prepend($model->resolveUrl($record->locale, $model->baseUrlSegment($record->locale)) . '/');
        }
    }
}