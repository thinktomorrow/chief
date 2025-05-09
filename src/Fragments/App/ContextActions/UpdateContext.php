<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\ContextActions;

final class UpdateContext
{
    private string $contextId;

    private array $allowedSites;

    private array $activeSites;

    private ?string $title;

    public function __construct(string $contextId, array $allowedSites, array $activeSites, ?string $title = null)
    {
        $this->contextId = $contextId;
        $this->allowedSites = $allowedSites;
        $this->activeSites = $activeSites;
        $this->title = $title;
    }

    public function getContextId(): string
    {
        return $this->contextId;
    }

    public function getAllowedSites(): array
    {
        return $this->allowedSites;
    }

    public function getActiveSites(): array
    {
        return $this->activeSites;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }
}
