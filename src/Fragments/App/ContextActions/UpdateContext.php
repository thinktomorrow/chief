<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\ContextActions;

final class UpdateContext
{
    private string $contextId;

    private array $sites;

    private ?string $title;

    public function __construct(string $contextId, array $sites, ?string $title = null)
    {
        $this->contextId = $contextId;
        $this->sites = $sites;
        $this->title = $title;
    }

    public function getContextId(): string
    {
        return $this->contextId;
    }

    public function getSites(): array
    {
        return $this->sites;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }
}
