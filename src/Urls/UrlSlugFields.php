<?php

namespace Thinktomorrow\Chief\Urls;

use Thinktomorrow\Chief\Concerns\Translatable\TranslatableContract;
use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Management\Assistants\UrlAssistant;
use Thinktomorrow\Chief\Urls\ProvidesUrl\ProvidesUrl;

class UrlSlugFields extends Fields
{
    public static function fromModel(ProvidesUrl $model)
    {
        $fields = self::initEmptyFields($model);

        self::fillWithExistingValues($model, $fields);

        return $fields;
    }

    /**
     * @param ProvidesUrl $model
     * @return UrlSlugFields
     */
    private static function initEmptyFields(ProvidesUrl $model): self
    {
        $wildCardField = UrlSlugWildcardField::make('url-slugs.' . UrlAssistant::WILDCARD)
            ->name('url-slugs[' . UrlAssistant::WILDCARD . ']')
            ->label('Default link')
            ->description('Standaard link die altijd van toepassing is indien er geen taalspecifieke link voorhanden is. Laat deze leeg indien je deze link in bepaalde talen niet wilt beschikbaar maken.');

        $fields = new static([$wildCardField]);

        if ( ! static::expectsLocalizedSlugs($model)) return $fields;

        foreach ($model->availableLocales() as $locale) {
            $fields['url-slugs.' . $locale] = UrlSlugField::make('url-slugs.' . $locale)
                                                ->setBaseUrlSegment($model->baseUrlSegment($locale))
                                                ->prepend($model->resolveUrl($locale, $model->baseUrlSegment($locale)) .'/')
                                                ->name('url-slugs[' . $locale . ']')
                                                ->label($locale . ' link');
        }

        return $fields;
    }

    /**
     * Does the admin need the option to define different url slugs per locale?
     *
     * @param TranslatableContract $model
     * @return bool
     */
    private static function expectsLocalizedSlugs(TranslatableContract $model): bool
    {
        return ($model->availableLocales() > 1);
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