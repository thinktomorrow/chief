<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Urls\App\Actions\Redirects;

final class CreateRedirectTo
{
    private string $targetId;

    private string $redirectSlug;

    public function __construct(string $targetId, string $redirectSlug)
    {
        $this->targetId = $targetId;
        $this->redirectSlug = $redirectSlug;
    }

    public function getTargetId(): string
    {
        return $this->targetId;
    }

    public function getRedirectSlug(): string
    {
        return $this->redirectSlug;
    }
}
