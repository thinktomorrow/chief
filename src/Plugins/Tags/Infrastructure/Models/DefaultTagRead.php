<?php

namespace Thinktomorrow\Chief\Plugins\Tags\Infrastructure\Models;

use Thinktomorrow\Chief\Plugins\Tags\App\Read\TagRead;
use Thinktomorrow\Chief\Plugins\Tags\Domain\Model\TagGroupId;
use Thinktomorrow\Chief\Plugins\Tags\Domain\Model\TagId;

class DefaultTagRead implements TagRead
{
    private TagId $tagId;
    private ?TagGroupId $tagGroupId;
    private string $label;
    private ?string $color;
    private array $data;
    private int $usages;

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
        $model->usages = $data['usages'] ?? 0;
        $model->data = $data['data'] ?? [];

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

    public function getUsages(): int
    {
        return $this->usages;
    }

    public function getData(string $key, string $index = null, $default = null)
    {
        $key = $index ? $key .'.'.$index : $key;

        return data_get($this->data, $key, $default);
    }
}
