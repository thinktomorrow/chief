<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields\Types;

use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\ManagedModels\Fields\Field;

class ImageField extends MediaField implements Field
{
    protected array $customValidationRules = [
        'required' => 'imagefield_required',
        'mimetypes' => 'imagefield_mimetypes',
        'dimensions' => 'imagefield_dimensions',
        'min' => 'imagefield_min',
        'max' => 'imagefield_max',
    ];

    public static function make(string $key): Field
    {
        return (new static(new FieldType(FieldType::IMAGE), $key))
            ->locales([config('app.fallback_locale', 'nl')])
            ->valueResolver(function ($model = null, $locale = null, ImageField $field) {
                return $field->getMedia($model, $locale);
            });
    }

    protected function getLocalizedNameFormat(): string
    {
        return 'images.:name.:locale';
    }

    /**
     * @return \stdClass[]
     *
     * @psalm-return list<\stdClass>
     */
    public function getMedia(HasAsset $model = null, ?string $locale = null)
    {
        if (! $model) {
            return [];
        }

        $images = [];
        $locale = $locale ?? app()->getLocale();

        $assets = $model->assetRelation->where('pivot.type', $this->getKey())->filter(function ($asset) use ($locale) {
            return $asset->pivot->locale == $locale;
        })->sortBy('pivot.order');

        foreach ($assets as $asset) {
            $images[] = (object)[
                'id' => $asset->id,
                'filename' => $asset->filename(),
                'url' => $asset->url(),
            ];
        }

        return $images;
    }
}
