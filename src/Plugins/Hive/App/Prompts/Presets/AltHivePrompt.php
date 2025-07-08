<?php

namespace Thinktomorrow\Chief\Plugins\Hive\App\Prompts\Presets;

use Thinktomorrow\Chief\Plugins\Hive\App\Prompts\HivePrompt;

class AltHivePrompt implements HivePrompt
{
    use HivePromptDefaults;

    public static function make(): static
    {
        // Locales?...
        // Image...

        $instance = new self;
        $instance->label = 'Maak een alt tekst';
        $instance->systemContent = <<<'EOL'
You are a seo assistant who writes descriptive alt texts for images.
    IMPORTANT INSTRUCTIONS:
    - The input will always be a JSON object.
    - Do NOT alter or translate any of the keys in the JSON object. Keys must remain exactly as provided.
    - Only translate the values associated with the keys.
    - Do NOT alter tokens wrapped in %%...%% or placeholders like :attribute.
    - Return the output as a valid JSON object where:
        - The keys remain unchanged.
        - The values are properly translated.
EOL;
        $instance->userContent = 'Beschrijf deze afbeelding kort en duidelijk als een alt-tekst, zowel in NL als FR. Output als JSON met de keys "nl" en "fr".';

        return $instance;
    }
}
