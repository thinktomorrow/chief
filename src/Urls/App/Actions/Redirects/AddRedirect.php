<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Urls\App\Actions\Redirects;

final class AddRedirect
{
    private string $redirectId;

    private string $targetId;

    public function __construct(string|int $redirectId, string|int $targetId)
    {
        $this->redirectId = (string) $redirectId;
        $this->targetId = (string) $targetId;
    }

    public function getRedirectId(): string
    {
        return $this->redirectId;
    }

    public function getTargetId(): string
    {
        return $this->targetId;
    }
}
