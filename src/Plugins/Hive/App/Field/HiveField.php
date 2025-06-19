<?php

namespace Thinktomorrow\Chief\Plugins\Hive\App\Field;

use Thinktomorrow\Chief\Plugins\Hive\App\Prompts\HivePrompt;

interface HiveField
{
    public function hive(array $hivePrompts): static;

    //    public function getHivePayload(?string $locale = null): array;

    public function disableHive(): static;

    public function isHiveEnabled(): bool;

    /** @return HivePrompt[] */
    public function getHivePrompts(): array;
}
