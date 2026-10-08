<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Authentication\Events;

use Thinktomorrow\Chief\Admin\Users\User;

final readonly class ChiefLoginCompleted
{
    /** @param array{name: string, id?: string} $actorSnapshot */
    private function __construct(
        public string $actorType,
        public array $actorSnapshot,
        public string $outcome,
        public array $context,
        public string $occurredAt,
    ) {}

    public static function succeeded(User $admin): self
    {
        return new self('admin', ['name' => $admin->fullname, 'id' => (string) $admin->getKey()], 'success', [], now()->toIso8601String());
    }

    public static function failed(string $email): self
    {
        return new self('external', ['name' => 'Unverified login attempt'], 'failure', ['submitted_email' => $email], now()->toIso8601String());
    }
}
