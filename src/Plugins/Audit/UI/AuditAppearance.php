<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\UI;

final readonly class AuditAppearance
{
    public function __construct(
        public string $label,
        public string $icon,
        public string $color,
        public string $priority,
    ) {}

    public static function forType(string $type): self
    {
        $settings = config('chief-audit.types', [])[$type] ?? [];

        return new self(
            $settings['label'] ?? $type,
            $settings['icon'] ?? 'information-circle',
            $settings['color'] ?? 'grey',
            ($settings['priority'] ?? null) === 'secondary' ? 'secondary' : 'primary',
        );
    }
}
