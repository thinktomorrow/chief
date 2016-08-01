<?php

namespace App\Console\Chief\Tasks;

use App\Console\Chief\ChiefTask;
use App\Console\Chief\ChiefTaskContract;

class Environment extends ChiefTask implements ChiefTaskContract
{
    public function handle()
    {
        collect(['.env','.env.production','.env.staging'])->each(function($filename){
            $this->copyEnvironmentFile($filename);
        });
    }

    private function copyEnvironmentFile($filename)
    {
        if(!file_exists(base_path($filename)))
        {
            echo exec('cp .env.example ' . $filename);
            $this->setApplicationKeyInEnvironmentFile($filename);
            $this->console->comment('Creating ['.$filename.'] environment file with new application key');
        }
        else
        {
            $this->console->comment('Environment file ['.$filename.'] already exists.');
        }
    }

    private function setApplicationKeyInEnvironmentFile($filename)
    {
        file_put_contents(base_path($filename), str_replace(
            'APP_KEY='.config('app.key'),
            'APP_KEY='.$this->generateRandomKey(),
            file_get_contents(base_path($filename))
        ));
    }
    /**
     * Generate a random key for the application.
     *
     * @return string
     */
    private function generateRandomKey()
    {
        return 'base64:'.base64_encode(random_bytes(
            config('app.cipher') == 'AES-128-CBC' ? 16 : 32
        ));
    }
}