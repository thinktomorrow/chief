<?php

namespace Thinktomorrow\Chief\App\Console;

class ChiefPublishCommand extends BaseCommand
{
    protected $signature = 'chief:publish {part = which chief part do you pick to publish to your project files?}';
    protected $description = 'Customize a chief admin part for your project';

    public function handle()
    {
        if(!$this->hasArgument('part')) {
            $part = $this->ask('which part');
        } else {
            $part = $this->argument('part');
        }

        $this->info($part . ' published');
    }
}
