<?php

namespace App\Console\Chief;

use Illuminate\Console\Command;

class ChiefTask
{
    protected $console;

    public function setConsole(Command $console)
    {
        $this->console = $console;
        return $this;
    }

    protected function copyWithParameters($source, $destination)
    {
        $content = file_get_contents($source);
        $content = str_replace('##PROJECT_NAME##',ucfirst(ChiefConfig::project()),$content);
        file_put_contents($destination,$content);
    }
}
