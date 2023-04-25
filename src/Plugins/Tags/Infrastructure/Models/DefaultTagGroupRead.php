<?php

namespace Thinktomorrow\Chief\Plugins\Tags\Infrastructure\Models;

use Thinktomorrow\Chief\Plugins\Tags\App\Read\TagGroupRead;
use Thinktomorrow\Chief\Plugins\Tags\Domain\Model\TagGroupId;

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
