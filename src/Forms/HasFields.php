<?php

namespace Thinktomorrow\Chief\Forms;

use Thinktomorrow\Chief\Forms\Fields\Field;

trait HasFields
{
    public function getFields(): Fields
    {
        return Fields::make($this->getFlattenedFields($this->getComponents()));
    }

    private function getFlattenedFields(array $children): array
    {
        $fields = [];

        foreach ($children as $child) {
            if ($child instanceof Field) {
                $fields[] = $child;
            }

            $fields = array_merge($fields, $this->getFlattenedFields($child->getComponents()));
        }

        return $fields;
    }
}
