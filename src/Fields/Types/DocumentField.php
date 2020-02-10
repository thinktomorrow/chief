<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\Types;

use Thinktomorrow\AssetLibrary\HasAsset;

class DocumentField extends MediaField implements Field
{
    public static function make(string $key): Field
    {
        return (new static(new FieldType(FieldType::DOCUMENT), $key))
            ->locales([config('app.fallback_locale', 'nl')]);
    }

    protected function getMedia(HasAsset $model, ?string $locale = null)
    {
        $documents = [];

        $builder = $model->assets($this->getKey(), $locale);

        foreach ($builder as $asset) {
            $documents[] = $asset;
        }

        return $documents;
    }
}
