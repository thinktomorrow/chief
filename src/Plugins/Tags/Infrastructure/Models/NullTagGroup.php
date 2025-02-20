<?php

namespace Thinktomorrow\Chief\Plugins\Tags\Infrastructure\Models;

use Thinktomorrow\Chief\Plugins\Tags\App\Read\TagGroupRead;

class NullTagGroup implements TagGroupRead
{
    private string $label;

    private array $data;

    private function __construct() {}

    public static function fromMappedData(array $data): static
    {
        $model = new static;

        $model->label = $data['label'] ?? 'Algemeen';
        $model->data = $data['data'] ?? [];

        return $model;
    }

    public function getTagGroupId(): string
    {
        return '';
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getData(string $key, ?string $locale = null, $default = null)
    {
        $key = $locale ? $key.'.'.$locale : $key;

        return data_get($this->data, $key, $default);
    }
}
