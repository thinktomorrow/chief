<?php

namespace App\Console\Chief;

use App\Console\Chief\Tasks\Composer;
use App\Console\Chief\Tasks\Domain;
use App\Console\Chief\Tasks\Environment;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ChiefSetupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chief:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prepare the chief environment for your project';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->globalConfiguration();

        $tasks = [
            Environment::class,
            Composer::class,
//            Domain::class, no domain classes for the time being
        ];

        foreach($tasks as $task)
        {
            app($task)->setConsole($this)
                      ->handle();
        }

        $this->info('You strong bear. You finished setup. Be wise and remember to:');
        $this->info('- set db credentials and bugsnag key for each environment');
        $this->info('- add the mail credentials so you can communicate with other clans.');
        $this->info('- have fun creating this white man invention.');
    }

    public function globalConfiguration()
    {
        $project = $this->ask('Projectname (required)');
        $client = $this->ask('Client (required)');
        $url = $this->ask('Site url','https://'.Str::slug($project).'.be');
        $namespace = $this->ask('Namespace',Str::slug($project));

        (new ChiefConfig())
            ->set('mtime',filemtime(base_path('server.php')))
            ->set('project',$project)
            ->set('client',$client)
            ->set('url',$url)
            ->set('namespace',$namespace);
    }
}
