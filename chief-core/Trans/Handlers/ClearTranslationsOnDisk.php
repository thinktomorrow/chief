<?php

namespace Chief\Trans\Handlers;

use League\Flysystem\Filesystem;

class ClearTranslationsOnDisk
{
    /**
     * Local filesystem. Already contains the path to our translation files
     * e.g. storage/app/trans
     *
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Clear cached translation files
     *
     * @param null $locale
     * @return bool
     */
    public function clear($locale = null)
    {
        if($locale) $this->filesystem->deleteDir($locale);

        foreach($this->filesystem->listContents() as $content)
        {
            if($content['type'] == 'dir') $this->filesystem->deleteDir($content['path']);
            else $this->filesystem->delete($content['path']);
        }
    }
}