<?php

namespace Thinktomorrow\Chief\Plugins\Hive\App\Prompts;

use Livewire\Wireable;

interface HivePrompt extends Wireable
{
    public function getLabel(): string;

    //    public function getPayload(Field $field): array;

    public function prompt(array $payload): static;

    //    public function getSuggestions(): array;
}
