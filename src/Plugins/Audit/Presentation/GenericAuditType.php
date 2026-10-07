<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Presentation;

final readonly class GenericAuditType implements AuditType
{
    /** @param array<string, mixed> $defaults */
    public function __construct(private string $type, private array $defaults = []) {}

    public function label(): string
    {
        return $this->defaults['label'] ?? $this->type;
    }

    public function icon(): string
    {
        return $this->defaults['icon'] ?? 'information-circle';
    }

    public function color(): string
    {
        return $this->defaults['color'] ?? 'grey';
    }

    public function priority(): string
    {
        return $this->defaults['priority'] ?? 'primary';
    }
}
