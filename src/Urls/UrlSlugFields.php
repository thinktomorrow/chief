<?php

namespace Thinktomorrow\Chief\Urls;

use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Management\Assistants\UrlAssistant;
use Thinktomorrow\Chief\Urls\ProvidesUrl\ProvidesUrl;

class UrlSlugFields extends Fields
{
    public static function fromModel(ProvidesUrl $model)
    {
        $fields = [];
        $records = MemoizedUrlRecord::getByModel($model)->reject(function($record){
            return $record->isRedirect();
        })->sortBy('locale');

        foreach($records as $record)
        {
            if($record->isWildCard()) {

                $fields[] = UrlSlugWildcardField::make('url-slugs.'.UrlAssistant::WILDCARD)
                    ->setUrlRecord($record)
                    ->setBaseUrlSegment($model->baseUrlSegment())
                    ->name('url-slugs['.UrlAssistant::WILDCARD.']')
                    ->label('Algemene link');

                continue;
            }

            $fields[] = UrlSlugField::make('url-slugs.'.$record->locale)
                    ->setUrlRecord($record)
                    ->setBaseUrlSegment($model->baseUrlSegment($record->locale))
                    ->name('url-slugs['.$record->locale.']')
                    ->label($record->locale . ' link')
                    ->prepend($model->url($record->locale));
        }

        return new static($fields);
    }

//    public function get(): array
//    {
//        $records = UrlRecord::getByModel($this->model);
//ddd($records);
//        // Remove base url segments per record
//
//
//        // the same slugs are reduces to one wildcard entry
//
//        // showLocalizedSlugs: bool
//
//        // entry: Field, previews
//        foreach($records as $record)
//        {
//
//        }
//
//        $displayGeneralLink = true;
//
//        foreach($records as $existingUrlRecord)
//        {
//            $displayGeneralLink = false;
//
//            if($existingUrlRecord->isWildCard()){
//                $displayGeneralLink = true;
//                break;
//            }
//        }
//
//        $availableLocales = $manager->model()->availableLocales();
//
//        return [];
//    }
}