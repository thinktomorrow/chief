<?php

namespace Thinktomorrow\Chief\App\Console;

class Seed extends BaseCommand
{
    protected $signature = 'chief:seed
                            {seeder=DatabaseSeeder : the classname of the seeder. }
                            {--force}';
    protected $description = 'This will run the seeders to inject new data into your project database.';

    public function handle()
    {
        if (app()->environment() != 'local' && ! $this->option('force')) {
            throw new \Exception('You can only run the seeder in the local environment since this will inject a ton of default data');
        }

        if (app()->environment() != 'local' && $this->option('force')) {
            if (! $this->confirm('You are about to inject default seeding data in the ' . app()->environment() . ' database! Are you sure?')) {
                $this->info('You are welcome. I have just saved your job.');

                return;
            }
        }

        $seederClass = $this->argument('seeder');
        app($seederClass)->run();

        $this->info($seederClass . ' has run successfully.');
    }
}
