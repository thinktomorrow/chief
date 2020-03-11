<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\Types;

use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\AssetLibrary\HasAsset;

class FileField extends MediaField implements Field
{
    protected $customValidationRules = [
        'required'   => 'filefield_required',
        'mimetypes'  => 'filefield_mimetypes',
        'dimensions' => 'filefield_dimensions',
        'min'        => 'filefield_min',
        'max'        => 'filefield_max',
    ];

    public static function make(string $key): Field
    {
        return (new static(new FieldType(FieldType::FILE), $key))
            ->locales([config('app.fallback_locale', 'nl')]);
    }

    protected function getMedia(HasAsset $model, ?string $locale = null)
    {
        $files = [];
        $locale = $locale ?? app()->getLocale();

        $assets = $model->assetRelation->where('pivot.type', $this->getKey())->filter(function ($asset) use ($locale) {
            return $asset->pivot->locale == $locale;
        })->sortBy('pivot.order');

        /** @var Asset $asset */
        foreach ($assets as $asset) {
            $files[] = (object)[
                'id'       => $asset->id,
                'filename' => $asset->filename(),
                'url'      => $asset->url(),
                'mimetype' => $asset->getMimeType(),
                'size'     => $asset->getSize(),
            ];
        }

        return $files;
    }
}
