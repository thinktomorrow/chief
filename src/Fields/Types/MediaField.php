<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Fields\Types;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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
        $name = $this->name();

        if (strpos($name, ':locale')) {
            return preg_replace('#(:locale)#', $locale, $name);
        }

        return $name.'[files]['.$locale.']';
        return 'files['.$name.'][trans]['.$locale.']';
    }

    public function getFieldValue(Model $model, $locale = null)
    {
        return $this->getMedia($model, $locale);
    }

    private function getMedia(HasMedia $model, $locale = null)
    {
        DB::enableQueryLog();

        $images = [];
        $builder = $model->assets()->where('asset_pivots.type', $this->key());

        if($locale) {
            $builder = $builder->where('asset_pivots.locale', $locale);
        }
//        $results = $builder->get();
//        ddd(DB::getQueryLog(), $results);
        foreach ($builder->get() as $asset) {

            if(!isset($images[$asset->pivot->locale])) {
                $images[$asset->pivot->locale] = [];
            }

            $images[$asset->pivot->locale][] = (object)[
                'id'       => $asset->id,
                'filename' => $asset->getFilename(),
                'url'      => $asset->getFileUrl(),
            ];
        }

        return [$this->key() => ['files' => $images]];
    }
}
