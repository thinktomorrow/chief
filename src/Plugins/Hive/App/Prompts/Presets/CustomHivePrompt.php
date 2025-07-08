<?php

namespace Thinktomorrow\Chief\Plugins\Hive\App\Prompts\Presets;

use Thinktomorrow\Chief\Plugins\Hive\App\Prompts\HivePrompt;

class CustomHivePrompt implements HivePrompt
{
    use HivePromptDefaults;

    public static function make(string $label, ?string $systemContent, string $userContent): self
    {
        $instance = new self;
        $instance->label = $label;
        $instance->systemContent = $systemContent;
        $instance->userContent = $userContent;
        $instance->temperature = config('chief-hive.temperature');
        $instance->maxTokens = config('chief-hive.max_tokens');

        return $instance;
    }

    public function setTemperature(float $temperature): self
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function setMaxTokens(int $maxTokens): self
    {
        $this->maxTokens = $maxTokens;

        return $this;
    }
}
