<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\ContextActions;

use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

final class CreateContext
{
    private ModelReference $modelReference;

    private array $allowedSites;

    private array $activeSites;

    private ?string $title;

    public function __construct(ModelReference $modelReference, array $allowedSites, array $activeSites, ?string $title = null)
    {
        $this->modelReference = $modelReference;
        $this->allowedSites = $allowedSites;
        $this->activeSites = $activeSites;
        $this->title = $title;
    }

    public function getModelReference(): ModelReference
    {
        return $this->modelReference;
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
