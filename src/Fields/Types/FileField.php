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
            ->locales([config('app.fallback_locale', 'nl')])
            ->valueResolver(function ($model = null, $locale = null, FileField $field) {
                return $field->getMedia($model, $locale);
            });
    }

    public function getMedia(HasAsset $model = null, ?string $locale = null)
    {
        if (!$model) {
            return [];
        }

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
                'thumbUrl' => $asset->url('thumb'),
                'mimetype' => $asset->getMimeType(),
                'isImage'  => ($asset->getExtensionType() == 'image'),
                'size'     => $asset->getSize(),
            ];
        }

        return $files;
    }
}
