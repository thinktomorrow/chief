<?php

namespace Thinktomorrow\Chief\App\Console;

class RefreshDatabase extends BaseCommand
{
    protected $signature = 'chief:scaffold {--reset}';
    protected $description = 'This will scaffold a few records to get started for development.';

    /**
     * @return void
     */
    public function handle()
    {
        if (app()->environment() != 'local') {
            throw new \Exception('Aborting. This command is dangerous and only meant for your local environment.');
        }

        if ($this->option('reset')) {
            if (! $this->confirm('You are about to force reset the database in the ' . app()->environment() . ' environment! ARE YOU SURE?')) {
                $this->info('aborting.');

                return;
            }
        }

        // loop over all managed models

        // reset entries if requested

        // Add 10 dummy entries + make sure to enter page fragments as well.

        // Include Our Chief factories for this command
//        app(ModelFactory::class)->load(realpath(dirname(__DIR__) . '/../database/factories'));
//
//        $this->info('Scaffolding some entries...');
//        factory(Page::class, 5)->create();

        $this->info('Great. We\'re done here. NOW START HACKING!');
    }
}
