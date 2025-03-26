<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\ContextActions;

final class DeleteContext
{
    private string $contextId;

    public function __construct(string $contextId)
    {
        $this->contextId = $contextId;
    }

    public function getContextId(): string
    {
        return $this->contextId;
    }
}
