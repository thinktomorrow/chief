<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Fields\Types;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;

class DocumentField extends Field
{
    public static function make(string $key)
    {
        return new static(new FieldType(FieldType::DOCUMENT), $key);
    }

    public function multiple($flag = true)
    {
        $this->values['multiple'] = $flag;

        return $this;
    }

    public function getFieldValue(Model $model, $locale = null)
    {
        return $this->getMedia($model, $locale);
    }

    private function getMedia(HasMedia $model, $locale = null)
    {
        $documents = [$this->key() => []];

        foreach ($model->getAllFiles()->groupBy('pivot.type') as $type => $assetsByType) {
            foreach ($assetsByType as $asset) {
                $documents[$type][] = $asset;
            }
        }

        return $documents;
    }
}
