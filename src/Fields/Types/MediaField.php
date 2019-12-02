<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Fields\Types;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\AssetLibrary\HasAsset;

class MediaField extends Field
{
    public static function make(string $key)
    {
        return new static(new FieldType(FieldType::MEDIA), $key);
    }

    public function multiple($flag = true)
    {
        $this->values['multiple'] = $flag;

        return $this;
    }

    public function translateName($locale)
    {
        $name = parent::name();

        if (strpos($name, ':locale')) {
            return preg_replace('#(:locale)#', $locale, $name);
        }

        return 'files['.$name.']['.$locale.']';
    }

    public function name(string $name = null)
    {
        if (!is_null($name)) {
            $this->values['name'] = $name;

            return $this;
        }

        return 'files['. ($this->values['name'] ?? $this->key()).']';
    }

    public function sluggifyName()
    {
        return trim(str_replace(['[', ']'], '-', $this->name()), '-');
    }

    public function getFieldValue(Model $model, $locale = null)
    {
        return $this->getMedia($model, $locale);
    }

    private function getMedia(HasAsset $model, $locale = null)
    {
        $images = [];
        $locale = $locale ?? app()->getLocale();

        $assets = $model->assetRelation->where('pivot.type', $this->key())->filter(function($asset) use($locale){
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
