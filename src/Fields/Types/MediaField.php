<?php declare(strict_types = 1);

namespace Thinktomorrow\Chief\Fields\Types;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\AssetLibrary\HasAsset;

class MediaField extends AbstractField implements Field
{
    protected $allowMultiple = false;

    public static function make(string $key): Field
    {
        // All media are set to be localized - as expected by asset library
        return (new static(new FieldType(FieldType::MEDIA), $key))
                ->locales([config('app.fallback_locale', 'nl')]);
    }

    public function multiple($flag = true)
    {
        $this->allowMultiple = $flag;

        return $this;
    }

    public function allowMultiple(): bool
    {
        return $this->allowMultiple;
    }

    protected function getLocalizedNameFormat(): string
    {
        return 'files.:name.:locale';
    }

    public function sluggifyName()
    {
        return trim(str_replace(['[', ']'], '-', $this->getName()), '-');
    }

    public function getValue(Model $model = null, string $locale = null)
    {
        return $this->getMedia($model, $locale);
    }

    private function getMedia(HasAsset $model, $locale = null)
    {
        $images = [];
        $locale = $locale ?? app()->getLocale();

        $assets = $model->assetRelation->where('pivot.type', $this->getKey())->filter(function ($asset) use ($locale) {
            return $asset->pivot->locale == $locale;
        })->sortBy('pivot.order');

        foreach ($assets as $asset) {
            $images[] = (object)[
                'id'       => $asset->id,
                'filename' => $asset->filename(),
                'url'      => $asset->url(),
            ];
        }

        return $images;
    }
}
