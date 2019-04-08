<?php

namespace Thinktomorrow\Chief\Fields;

class ImageRequiredValidator
{
    public function validate($attribute, $values, $params, $validator)
    {
        $new = isset($values['new']) ? count($values['new']) : 0;
        $replace = isset($values['replace']) ? count($values['replace']) : 0;
        $delete = isset($values['delete']) ? count($values['delete']) : 0;

        if ($new + $replace - $delete > 0) {
            return true;
        }

        return false;
    }
}
