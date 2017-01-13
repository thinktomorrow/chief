<?php

namespace App\Console\Chief\Tasks;

use App\Console\Chief\ChiefConfig;
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
            $this->setEnvironmentFlag($filename);
            $this->setDebugFlag($filename);
            $this->setUrl($filename);
            $this->console->comment('Creating ['.$filename.'] environment file with new application key');
        }
        else
        {
            $this->console->comment('Environment file ['.$filename.'] already exists. You should delete this manually first.');
        }
    }

    private function setDebugFlag($filename)
    {
        $newvalue = ($filename == '.env') ? 'true' : 'false';

        $this->changeValue($filename,'APP_DEBUG',$newvalue);
    }

    private function setEnvironmentFlag($filename)
    {
        // Environment is taken from last part of string
        $environment = substr($filename,strrpos($filename,'.')+1);
        if($environment == 'env') $environment = 'local';

        $this->changeValue($filename,'APP_ENV',$environment);
    }

    private function setUrl($filename)
    {
        $environment = substr($filename,strrpos($filename,'.')+1);
        if($environment == 'env') return;
        if($environment == 'production') $url = ChiefConfig::url();
        if($environment == 'staging') $url = ChiefConfig::project().'.thinktomorrow.be';

        $this->changeValue($filename,'APP_URL',$url);
    }

    private function setApplicationKeyInEnvironmentFile($filename)
    {
        $this->changeValue($filename,'APP_KEY',$this->generateRandomKey());
    }

    private function changeValue($filename,$key,$value)
    {
        $content = preg_replace('#'.$key.'=[^\\n]*#',$key.'='.$value, file_get_contents(base_path($filename)));

        file_put_contents(base_path($filename), $content);
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