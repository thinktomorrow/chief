<?php

namespace App\Console\Chief\Tasks;

use App\Console\Chief\ChiefTask;
use App\Console\Chief\ChiefTaskContract;

class Domain extends ChiefTask implements ChiefTaskContract
{
    public function handle()
    {
        $copyDomainClasses = true;

        if(is_dir(base_path('src')))
        {
            $copyDomainClasses = $this->console->confirm('/src directory already exists? Are you sure you want to refresh it?', false);
        }

        if($copyDomainClasses)
        {
            $this->safeCopy(__DIR__.'/../default/src');
        }

    }

    protected function safeCopy($dir)
    {
        $handle = opendir($dir);

        echo "Directory handle: $handle\n";
        echo "Entries:\n";

        /* This is the correct way to loop over the directory. */
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {

                echo "$dir/$entry\n";

                $copyDir = true;

                if(is_dir(base_path('src/'.$entry)))
                {
                    $copyDir = $this->console->confirm('/src/'.$entry.' directory already exists? Overwrite it?', false);
                }

                if($copyDir)
                {
                    $this->recursiveCopy($dir.'/'.$entry,base_path('src/'.$entry));
                }
            }
        }

        closedir($handle);
    }

    protected function recursiveCopy($src,$dst) {
        $dir = opendir($src);

        if(!is_dir($dst))
        {
            mkdir($dst,0777,true);
        }

        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    $this->recursiveCopy($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    $this->copyWithParameters(
                        $src . '/' . $file,
                        $dst . '/' . $file
                    );
                }
            }
        }
        closedir($dir);
    }
}