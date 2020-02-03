<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\Types;

use Thinktomorrow\AssetLibrary\HasAsset;

class DocumentField extends MediaField implements Field
{
    public static function make(string $key): Field
    {
        return new static(new FieldType(FieldType::DOCUMENT), $key);
    }

    public function multiple($flag = true)
    {
        $this->values['multiple'] = $flag;

        return $this;
    }

//    public function translateName($locale)
//    {
//        $name = $this->name();
//
//        if (strpos($name, ':locale')) {
//            return preg_replace('#(:locale)#', $locale, $name);
//        }
//
//        return 'files[' . $name . '][' . $locale . ']';
//    }

//    public function getFieldValue(Model $model, $locale = null)
//    {
//        return $this->getMedia($model, $locale);
//    }

    private function getMedia(HasAsset $model, $locale = null)
    {
        $documents = [];

        $builder = $model->assets($this->getKey(), $locale);

        foreach ($builder as $asset) {
            $documents[] = $asset;
        }

        return $documents;
    }
}
