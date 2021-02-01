<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Old\Management\Assistants;

interface AssistedManager
{
    public function isAssistedBy(string $assistant): bool;

    public function assistant(string $assistant): Assistant;

    public function assistants(): array;
}
