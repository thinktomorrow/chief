<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Media\Application;

class MediaRequest
{
    const NEW = 'new';
    const REPLACE = 'replace';
    const DETACH = 'detach';

    /** @var array */
    private $items = [
        self::NEW     => [],
        self::REPLACE => [],
        self::DETACH  => [],
    ];

    public function add(string $key, MediaRequestInput $mediaRequest): self
    {
        $this->items[$key][] = $mediaRequest;

        return $this;
    }

    public function all(): array
    {
        return $this->items;
    }

    public function getByKey(string $key): array
    {
        return $this->items[$key];
    }
}
