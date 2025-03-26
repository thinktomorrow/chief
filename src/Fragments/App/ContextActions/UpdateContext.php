<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\ContextActions;

final class UpdateContext
{
    private string $contextId;

    private array $locales;

    private ?string $title;

    public function __construct(string $contextId, array $locales, ?string $title = null)
    {
        $this->contextId = $contextId;
        $this->locales = $locales;
        $this->title = $title;
    }

    public function getContextId(): string
    {
        return $this->contextId;
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
