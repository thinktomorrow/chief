<?php

namespace Thinktomorrow\Chief\Plugins\Hive\Drivers;

use Thinktomorrow\Chief\Plugins\Hive\App\Prompts\HivePrompt;
use Thinktomorrow\Chief\Plugins\Hive\App\Prompts\HivePromptResponse;

class DummyDriver implements Driver
{
    public function chat(HivePrompt $prompt): HivePromptResponse
    {
        // TODO: Implement request() method.
    }
}
