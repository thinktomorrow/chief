<?php

namespace Thinktomorrow\Chief\Admin\Tags\Read;

use Thinktomorrow\Chief\Admin\Tags\TagGroupId;
use Thinktomorrow\Chief\Admin\Tags\TagId;

class DefaultTagRead implements TagRead
{
    private TagId $tagId;
    private ?TagGroupId $tagGroupId;
    private string $label;
    private ?string $color;
    private array $data;

    private function __construct()
    {

    }

    public static function fromMappedData(array $data): static
    {
        $model = new static();

        $model->tagId = TagId::fromString($data['id']);
        $model->tagGroupId = $data['taggroup_id'] ? TagGroupId::fromString($data['taggroup_id']) : null;
        $model->label = $data['label'];
        $model->color = $data['color'] ?? null;

        return $model;
    }

    public function getTagId(): string
    {
        return $this->tagId->get();
    }

    public function getTagGroupId(): ?string
    {
        return $this->tagGroupId?->get();
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getColor(): string
    {
        return $this->color ?: '#999999';
    }

    public function getData(string $key, string $locale = null, $default = null)
    {
        $key = $locale ? $key .'.'.$locale : $key;

        return data_get($this->data, $key, $default);
    }
}
