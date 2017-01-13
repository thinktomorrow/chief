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
        $replacements = [
            '##PROJECT_NAME##' => ucfirst(ChiefConfig::project()), //Deprecated
            '##PROJECT##'      => ucfirst(ChiefConfig::project()),
            '##CLIENT##'       => ucfirst(ChiefConfig::client()),
            '##NAMESPACE##'    => ChiefConfig::namespace(),
            '##URL##'          => ChiefConfig::url(),
        ];

        $content = file_get_contents($source);
        $content = str_replace(array_keys($replacements), array_values($replacements), $content);
        file_put_contents($destination, $content);
    }
}
