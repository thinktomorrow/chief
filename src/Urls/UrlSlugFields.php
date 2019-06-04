<?php

namespace Thinktomorrow\Chief\Urls;

use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Urls\ProvidesUrl\ProvidesUrl;
use Thinktomorrow\Chief\Management\Assistants\UrlAssistant;

class UrlSlugFields extends Fields
{
    public static function fromModel(ProvidesUrl $model)
    {
        $fields = self::initEmptyFields($model->availableLocales(), $model);

        self::fillWithExistingValues($model, $fields);

        return $fields;
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function toArray(): array
    {
        $array = [];

        foreach($this->all() as $field) {
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
        // Add wildcard field as default
        $fields = new static([ $wildCardField = UrlSlugField::make('url-slugs.' . UrlAssistant::WILDCARD)
            ->name('url-slugs[' . UrlAssistant::WILDCARD . ']')
            ->label('')
        ]);

        if (count($locales) < 2) return $fields;

        // Add description to wildcard field only when there are locale values.
        $wildCardField->label('Default link')
                      ->description('Standaard link die altijd van toepassing is indien er geen taalspecifieke link voorhanden is. Laat deze leeg indien je deze link in bepaalde talen niet wilt beschikbaar maken.');

        foreach ($locales as $locale) {
            $fields['url-slugs.' . $locale] = UrlSlugField::make('url-slugs.' . $locale)
                                                ->setBaseUrlSegment($model->baseUrlSegment($locale))
                                                ->prepend($model->resolveUrl($locale, $model->baseUrlSegment($locale)) .'/')
                                                ->name('url-slugs[' . $locale . ']')
                                                ->label($locale . ' link');
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

            if ($record->isManagedAsWildCard()) {
                $fields['url-slugs.' . UrlAssistant::WILDCARD]
                    ->setUrlRecord($record)
                    ->setBaseUrlSegment($model->baseUrlSegment($record->locale));
                continue;
            }

            if(!isset($fields['url-slugs.'.$record->locale])) continue;

            $fields['url-slugs.'.$record->locale]
                ->setUrlRecord($record)
                ->setBaseUrlSegment($model->baseUrlSegment($record->locale))
                ->prepend($model->resolveUrl($record->locale, $model->baseUrlSegment($record->locale)) .'/');
        }
    }
}