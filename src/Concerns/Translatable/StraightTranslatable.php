<?php


namespace Thinktomorrow\Chief\Concerns\Translatable;

/**
 * Allows an eloquent model to have a column to be translatable.
 * This requires a translatableAttribute property on the model itself
 * to indicate the which attribute needs to be translatable.
 *
 */
trait StraightTranslatable
{
    public function setAttribute($key, $value)
    {
        if($key == $this->translatableAttribute && is_array($value)) {
            $value = json_encode($value);
        }

        return parent::setAttribute($key, $value);
    }

    public function getCasts()
    {
        return array_merge([$this->translatableAttribute => 'json'], parent::getCasts());
    }
}