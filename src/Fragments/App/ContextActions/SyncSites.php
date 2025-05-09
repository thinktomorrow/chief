<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\ContextActions;

use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

final class SyncSites
{
    private ModelReference $modelReference;

    private array $allowedSites;

    public function __construct(ModelReference $modelReference, array $allowedSites)
    {
        $this->modelReference = $modelReference;
        $this->allowedSites = $allowedSites;
    }

    public function getModelReference(): ModelReference
    {
        return $this->modelReference;
    }

    public function getAllowedSites(): array
    {
        return $this->allowedSites;
    }
}
