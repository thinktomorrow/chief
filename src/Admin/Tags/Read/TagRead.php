<?php

namespace Thinktomorrow\Chief\Admin\Tags\Read;

interface TagRead
{
    public static function fromMappedData(array $data): static;

    public function getTagId(): string;

    public function getTagGroupId(): ?string;

    public function getLabel(): string;

    public function getColor(): string;

    public function getData(string $key, string $locale = null, $default = null);
}
