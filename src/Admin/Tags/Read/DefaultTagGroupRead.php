<?php

namespace Thinktomorrow\Chief\Admin\Tags\Read;

use Thinktomorrow\Chief\Admin\Tags\TagGroupId;

class DefaultTagGroupRead implements TagGroupRead
{
    private TagGroupId $tagGroupId;
    private string $label;
    private array $data;

    private function __construct()
    {

    }

    public static function fromMappedData(array $data): static
    {
        $model = new static();

        $model->tagGroupId = TagGroupId::fromString($data['id']);
        $model->label = $data['label'];

        return $model;
    }

    public function getTagGroupId(): string
    {
        return $this->tagGroupId->get();
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getData(string $key, string $locale = null, $default = null)
    {
        $key = $locale ? $key .'.'.$locale : $key;

        return data_get($this->data, $key, $default);
    }
}
