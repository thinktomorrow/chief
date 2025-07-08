<?php

namespace Thinktomorrow\Chief\Plugins\Hive\App\Field;

use Thinktomorrow\Chief\Plugins\Hive\App\Prompts\HivePrompt;

trait HiveFieldDefaults
{
    protected bool $hiveEnabled = false;

    /** @var HivePrompt[] */
    protected array $hivePrompts = [];

    public function isHiveEnabled(): bool
    {
        return $this->hiveEnabled;
    }

    public function hive(array $hivePrompts = []): static
    {
        $this->hiveEnabled = true;
        $this->hivePrompts = array_map(fn ($promptClass) => $promptClass instanceof HivePrompt ? $promptClass : app($promptClass), $hivePrompts);

        foreach ($this->hivePrompts as $prompt) {
            if (! $prompt instanceof HivePrompt) {
                throw new \InvalidArgumentException('All hive prompts must implement the HivePrompt interface.');
            }
        }

        return $this;
    }

    public function getHivePayload(?string $locale = null, $livewireComponent = null): array
    {
        $payload = [];

        foreach ($this->hivePrompts as $prompt) {
            $payload = array_merge($payload, $prompt->getPayload($this, $locale, $livewireComponent));
        }

        return $payload;
    }

    public function disableHive(): static
    {
        $this->hiveEnabled = false;

        return $this;
    }

    /** @return HivePrompt[] */
    public function getHivePrompts(): array
    {
        return $this->hivePrompts;
    }
}
