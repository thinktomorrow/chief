<?php

namespace Thinktomorrow\Chief\Plugins\Tags\App\Read;

interface TagGroupRead
{
    public static function fromMappedData(array $data): static;

    public function getTagGroupId(): string;

    public function getLabel(): string;

    public function getData(string $key, ?string $locale = null, $default = null);
}
