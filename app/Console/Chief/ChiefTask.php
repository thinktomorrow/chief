<?php

namespace App\Console\Chief;

use Illuminate\Console\Command;

class ChiefTask
{
    protected $config;
    protected $console;

    public function setConfig(array $config)
    {
        $this->config = $config;
        return $this;
    }

    public function setConsole(Command $console)
    {
        $this->console = $console;
        return $this;
    }

    protected function copyWithParameters($source, $destination)
    {
        $content = file_get_contents($source);
        $content = str_replace('##PROJECT_NAME##',ucfirst($this->config['project']),$content);
        file_put_contents($destination,$content);
    }
}
