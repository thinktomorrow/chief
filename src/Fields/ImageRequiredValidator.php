<?php

namespace Thinktomorrow\Chief\Fields;

class ImageRequiredValidator
{
    public function validate($attribute, $values, $params, $validator)
    {
        if(!isset($validator->attributes()['files'])) return false;
        if(!isset($validator->attributes()['files'][$attribute])) return false;

        $values = $validator->attributes()['files'][$attribute];
        
        foreach($values as $locale => $value)
        {
            $new     = isset($value['new']) ? count($value['new']) : 0;
            $replace = isset($value['replace']) ? count($value['replace']) : 0;
            $delete  = isset($value['delete']) ? count($value['delete']) : 0;

            if ($new + $replace - $delete > 0) {
                return true;
            }
    
            return false;
        }
    }
}
