<?php

namespace Export\Export\Lines;

use Thinktomorrow\Chief\Plugins\Export\Export\Lines\FieldLine;

class RepeatFieldLine extends FieldLine
{
    public function __construct(string $modelReference, string $fieldKey, string $resourceLabel, string $modelLabel, string $fieldLabel, array $values)
    {
        parent::__construct($modelReference, $fieldKey, $resourceLabel, $modelLabel, $fieldLabel, $values);

        $this->encryptedReference = encrypt($this->modelReference.'|Repeat|'.$this->fieldKey);
    }
}
