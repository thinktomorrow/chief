<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Urls\App\Actions;

/**
 * Use this url as the active url for the given locale.
 * Redirects the current active url to this new active one.
 */
final class ReactivateUrl
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
