<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\ContextActions;

use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

final class CreateContext
{
    private ModelReference $modelReference;

    private array $locales;

    private ?string $title;

    public function __construct(ModelReference $modelReference, array $locales, ?string $title = null)
    {
        $this->modelReference = $modelReference;
        $this->locales = $locales;
        $this->title = $title;
    }

    public function getModelReference(): ModelReference
    {
        return $this->modelReference;
    }

    public function getLocales(): array
    {
        return $this->locales;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }
}
