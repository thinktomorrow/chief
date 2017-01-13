<?php

namespace App\Console\Chief;

use Illuminate\Console\Command;

interface ChiefTaskContract
{
    public function setConsole(Command $console);
}
