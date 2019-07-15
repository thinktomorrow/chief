<?php

namespace Thinktomorrow\Chief\Urls;

use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Urls\ProvidesUrl\ProvidesUrl;

class UrlSlugFields extends Fields
{
    public static function fromModel(ProvidesUrl $model)
    {
        $fields = self::initEmptyFields($model->availableLocales(), $model);

        self::fillWithExistingValues($model, $fields);

        return $fields;
    }

    public static function redirectsFromModel(ProvidesUrl $model)
    {
        $records = MemoizedUrlRecord::getByModel($model)->reject(function ($record) {
            return !$record->isRedirect();
        })->sortByDesc('created_at');

        $fields = new static([]);

        foreach ($records as $record) {
            $key = 'redirects-'.$record->locale.'-'.$record->slug;
            $fields[$key] = UrlSlugField::make($key)
                ->setUrlRecord($record)
                ->setBaseUrlSegment($model->baseUrlSegment($record->locale))
                ->prepend($model->resolveUrl($record->locale, $model->baseUrlSegment($record->locale)) . '/');
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
                                                ->prepend($model->resolveUrl($locale, $model->baseUrlSegment($locale)) .'/')
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
        $records = MemoizedUrlRecord::getByModel($model)->reject(function ($record) {
            return $record->isRedirect();
        })->sortBy('locale');

        foreach ($records as $record) {
            if (!isset($fields['url-slugs.'.$record->locale])) {
                continue;
            }

            $fields['url-slugs.'.$record->locale]
                ->setUrlRecord($record)
                ->setBaseUrlSegment($model->baseUrlSegment($record->locale))
                ->prepend($model->resolveUrl($record->locale, $model->baseUrlSegment($record->locale)) .'/');
        }
    }
}
