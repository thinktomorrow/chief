<?php

namespace App\Console\Chief;

use App\Console\Chief\Tasks\Composer;
use App\Console\Chief\Tasks\Domain;
use App\Console\Chief\Tasks\Environment;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ChiefSetupCommand extends Command
{
    static protected $config = [];

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
            app($task)->setConfig(static::$config)
                      ->setConsole($this)
                      ->handle();
        }

        $this->info('Chief setup finished.');
    }

    public function globalConfiguration()
    {
        $project = $this->ask('Projectname (required)');
        $client = $this->ask('Client (required)');
        $url = $this->ask('Site url',Str::slug($project).'.be');
        $namespace = $this->ask('Namespace',Str::slug($project));

        static::$config = array_merge(static::$config,[
            'mtime' => filemtime(base_path('server.php')),
            'project' => $project,
            'client' => $client,
            'url'   => $url,
            'namespace' => $namespace
        ]);
    }
}
