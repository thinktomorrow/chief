<?php

namespace Thinktomorrow\Chief\Plugins\Hive\Drivers;

use Thinktomorrow\Chief\Plugins\Hive\App\Prompts\HivePrompt;

class DummyDriver implements Driver
{
    public function chat(HivePrompt $prompt): mixed
    {
        return null;
    }
}
