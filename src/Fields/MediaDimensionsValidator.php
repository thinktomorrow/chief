<?php

namespace Thinktomorrow\Chief\Fields;

class MediaDimensionsValidator
{
    public function validate($attribute, $values, $params, $validator)
    {
        trap($values);
        $params = array_reduce($params, function ($result, $item) {
            [$key, $value] = array_pad(explode('=', $item, 2), 2, null);

            $result[$key] = $value;

            return $result;
        });

        unset($values['delete']);

        $result = [];

        foreach ($values as $types) {
            foreach ($types as $image) {
                if ($image) {
                    $file = json_decode($image)->output;

                    $width = $file->width;
                    $height = $file->height;

                    $result[] = !((isset($params['width']) && $params['width'] != $width) ||
                            (isset($params['min_width']) && $params['min_width'] > $width) ||
                            (isset($params['max_width']) && $params['max_width'] < $width) ||
                            (isset($params['height']) && $params['height'] != $height) ||
                            (isset($params['min_height']) && $params['min_height'] > $height) ||
                            (isset($params['max_height']) && $params['max_height'] < $height));
                }
            }
        }

        return !in_array(false, $result);
    }
}
