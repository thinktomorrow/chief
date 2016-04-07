<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

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
        $project = $this->ask('Projectname (required)');
        $client = $this->ask('Client (required)');
        $url = $this->ask('Site url',Str::slug($project).'.be');
        $namespace = $this->ask('Namespace',Str::slug($project));

        // Create .env from .env.example
        // Set application key in .env
        // Duplicate .env as .env.staging and .env.production if not already

        if(!file_exists(base_path('.env')))
        {
            echo exec('cp .env.example .env');
            $this->comment('created environment file with new application key');
            echo exec('php artisan key:generate');
        }else
        {
            $this->comment('Environment file already exists.');
        }


        dd($project,$namespace);
    }
}
