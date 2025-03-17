<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Actions;

final class AttachRootFragment
{
    private AttachFragment $attachFragment;

    public function __construct(AttachFragment $attachFragment)
    {
        $this->attachFragment = $attachFragment;
    }

    public function handle(string $contextId, string $fragmentId, int $order, array $data = []): void
    {
        $this->attachFragment->handle($contextId, $fragmentId, null, $order, $data);
    }
}
