<?php

namespace Thinktomorrow\Chief\Setup\Console;

use Thinktomorrow\Chief\Setup\Setup;

class SetupCommand
{
    protected $signature = 'chief:setup
                            {--force : just install it without confirming everything.}';

    protected $description = 'Installs and sets up Chief in your project.';

    public function handle()
    {
        app(Setup::class)->run();
    }
}