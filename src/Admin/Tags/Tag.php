<?php

namespace Thinktomorrow\Chief\Admin\Tags;

class Tag
{
    private string $label;
    private array $data;
    private TagGroupId $tagGroupId;

    public function __construct(readonly public TagId $tagId, TagGroupId $tagGroupId, string $label, array $data)
    {
        $this->label = $label;
        $this->data = $data;
        $this->tagGroupId = $tagGroupId;
    }

    public function getTagGroupId(): TagGroupId
    {
        return $this->tagGroupId;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function updateTagGroupId(TagGroupId $tagGroupId): void
    {
        $this->tagGroupId = $tagGroupId;
    }

    public function updateLabel(string $label): void
    {
        $this->label = $label;
    }

    public function updateData(array $data): void
    {
        $this->data = array_merge($this->data, $data);
    }
}
