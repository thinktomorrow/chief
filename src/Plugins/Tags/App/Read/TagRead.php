<?php

namespace Thinktomorrow\Chief\Plugins\Tags\App\Read;

use Illuminate\Support\Collection;

interface TagRead
{
    public static function fromMappedData(array $data): static;

    public function getTagId(): string;

    public function getTagGroupId(): ?string;

    public function getLabel(): string;

    public function getColor(): string;

    public function getUsages(): int;

    public function getOwnerReferences(): Collection;

    public function getData(string $key, ?string $index = null, $default = null);
}
