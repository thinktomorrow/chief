<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\Types;

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

        $builder = $model->assets($this->getKey(), $locale);

        foreach ($builder as $asset) {
            $files[] = $asset;
        }

        return $files;
    }
}
