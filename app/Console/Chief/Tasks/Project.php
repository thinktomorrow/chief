<?php

namespace App\Console\Chief\Tasks;

use App\Console\Chief\ChiefConfig;
use App\Console\Chief\ChiefTask;
use App\Console\Chief\ChiefTaskContract;

class Project extends ChiefTask implements ChiefTaskContract
{
    public function handle()
    {
        $setupProjectConfigFile = true;

        if(filemtime(base_path('config/project.php')) > ChiefConfig::mtime())
        {
            $setupProjectConfigFile = $this->console->confirm('/confirm/project.php file has been altered. Do you wish to override it with a fresh one?', false);
        }

        if($setupProjectConfigFile)
        {
            $this->copyWithParameters(
                base_path('app/Console/Chief/default/project.php'),
                base_path('config/project.php')
            );
        }
    }
}