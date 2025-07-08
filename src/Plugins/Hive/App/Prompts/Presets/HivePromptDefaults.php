<?php

namespace Thinktomorrow\Chief\Plugins\Hive\App\Prompts\Presets;

trait HivePromptDefaults
{
    public function getLabel(): string
    {
        return $this->label;
    }

    public function toLivewire()
    {
        return [
            'label' => $this->getLabel(),
        ];
    }

    public static function fromLivewire($value)
    {
        $object = new static;

        $object->label = $value['label'];

        return $object;
    }
}
