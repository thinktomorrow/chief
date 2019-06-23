<?php

namespace Thinktomorrow\Chief\Authorization\Console;

use Illuminate\Console\Command;
use Thinktomorrow\Chief\Authorization\Console\AuthorizationCommandHelpers;

class CreateDeveloper extends Command
{
    use AuthorizationCommandHelpers;

    protected $signature = 'chief:developer';
    protected $description = 'Create a new chief developer user';

    public function handle()
    {
        $this->call('chief:admin', ['--dev' => true]);
    }
}
