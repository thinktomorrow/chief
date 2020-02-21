<?php

namespace Thinktomorrow\Chief\App\Console;

use Illuminate\Support\Facades\Artisan;
use Thinktomorrow\Chief\Authorization\AuthorizationDefaults;

class CreateDeveloper extends BaseCommand
{
    protected $signature = 'chief:developer';
    protected $description = 'Create a new chief developer user';

    public function handle()
    {
        $this->call('chief:admin', ['--dev' => true]);
    }
}
