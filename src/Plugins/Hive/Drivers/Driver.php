<?php

namespace Thinktomorrow\Chief\Plugins\Hive\Drivers;

use Thinktomorrow\Chief\Plugins\Hive\App\Prompts\HivePrompt;

interface Driver
{
    public function chat(HivePrompt $prompt): mixed;
}
