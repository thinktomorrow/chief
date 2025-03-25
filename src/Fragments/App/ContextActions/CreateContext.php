<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\ContextActions;

use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

final class CreateContext
{
    private ModelReference $modelReference;

    private array $sites;

    private ?string $title;

    public function __construct(ModelReference $modelReference, array $sites, ?string $title = null)
    {
        $this->modelReference = $modelReference;
        $this->sites = $sites;
        $this->title = $title;
    }

    public function getModelReference(): ModelReference
    {
        return $this->modelReference;
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
