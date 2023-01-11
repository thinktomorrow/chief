<?php

namespace Thinktomorrow\Chief\Admin\Tags;

class Tag
{
    private TagState $tagState;
    private string $label;
    private array $data;

    public function __construct(readonly public TagId $tagId, TagState $tagState, string $label, array $data)
    {
        $this->tagState = $tagState;
        $this->label = $label;
        $this->data = $data;
    }

    public function getState(): TagState
    {
        return $this->tagState;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
