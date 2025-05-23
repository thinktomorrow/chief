<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Urls\App\Actions;

final class DeleteUrl
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
