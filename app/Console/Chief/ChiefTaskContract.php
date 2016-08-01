<?php

namespace App\Console\Chief;

use Illuminate\Console\Command;

interface ChiefTaskContract
{
    public function setConfig(array $config);
    public function setConsole(Command $console);
}
