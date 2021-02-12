<?php

namespace Thinktomorrow\Chief\ManagedModels\Fields\Types;

trait AllowsComponentTags
{
    abstract public function tag($tag);

    protected ?string $componentKey;

    public function component($componentKey)
    {
        $this->tag('component');
        $this->componentKey = $componentKey;

        return $this;
    }

    public function componentKey(): ?string
    {
        if(!isset($this->componentKey)) return null;

        return $this->componentKey;
    }
}
