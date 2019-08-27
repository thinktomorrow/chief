<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Fields\Types;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;

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

        return 'files['. ($this->values['name'] ?? $this->key() ).']';
    }

    public function getFieldValue(Model $model, $locale = null)
    {
        return $this->getMedia($model, $locale);
    }

    private function getMedia(HasMedia $model, $locale = null)
    {
        $images = [];

        $builder = $model->assets()->where('asset_pivots.type', $this->key());

        if ($locale) {
            $builder = $builder->where('asset_pivots.locale', $locale);
        }

        foreach ($builder->get() as $asset) {
            $images[] = (object)[
                'id'       => $asset->id,
                'filename' => $asset->getFilename(),
                'url'      => $asset->getFileUrl(),
            ];
        }

        return $images;
    }
}
