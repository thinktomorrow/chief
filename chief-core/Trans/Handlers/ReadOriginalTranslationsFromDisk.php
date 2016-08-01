<?php

namespace Chief\Trans\Handlers;

use League\Flysystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class ReadOriginalTranslationsFromDisk
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
     * Create new cached translation files based on database entries
     *
     * @param $locale
     * @param array $groups (filenames)
     * @param bool $flattened per group a flat array listing with dotted key and values
     * @return array
     */
    public function read($locale, array $groups = [], $flattened = true, $strict = true)
    {
        $translations = [];

        foreach($groups as $group)
        {
            $filepath = base_path('resources/lang/'.$locale.'/'.$group.'.php');
            if(!$strict && !file_exists($filepath)) continue;

            $translations[$group] = $this->readTranslationFile($filepath);
        }

        if($flattened)
        {
            return collect($translations)->map(function($values){
                return array_dot($values);
            })->toArray();
        }

        return $translations;
    }

    public function readLoosely($locale, array $groups = [], $flattened = true)
    {
        return $this->read($locale,$groups,$flattened,false);
    }

    private function readTranslationFile($path)
    {
        if (file_exists($path) && is_file($path)) {
            return require $path;
        }

        throw new FileNotFoundException("File does not exist at path {$path}");
    }
}