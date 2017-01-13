<?php

namespace App\Console\Chief\Tasks;

use App\Console\Chief\ChiefConfig;
use App\Console\Chief\ChiefTask;
use App\Console\Chief\ChiefTaskContract;

class Composer extends ChiefTask implements ChiefTaskContract
{
    public function handle()
    {
        $setupComposerFile = true;

        if(filemtime(base_path('composer.json')) > ChiefConfig::mtime())
        {
            $setupComposerFile = $this->console->confirm('Composer.json file has been altered. Do you wish to override it with a fresh one?', false);
        }

        if($setupComposerFile)
        {
            $this->copyWithParameters(
                base_path('app/Console/Chief/default/composer.json'),
                base_path('composer.json')
            );
        }
    }
}