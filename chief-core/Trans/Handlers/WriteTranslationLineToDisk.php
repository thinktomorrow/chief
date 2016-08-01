<?php

namespace Chief\Trans\Handlers;

use League\Flysystem\Filesystem;

class WriteTranslationLineToDisk
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
     * @param array $lines - flat array of key-value pairs e.g. foo.bar => 'translation of foo'
     */
    public function write($locale, array $lines = [])
    {
        $translations = $this->convertToTree($lines);

        foreach($translations as $section => $trans)
        {
            $path = $locale.'/'.$section.'.php';
            $content = "<?php\n\n return ".var_export($trans,true).";\n";

            $this->filesystem->put($path,$content);
        }
    }

    private function convertToTree(array $lines = [])
    {
        $translations = [];

        foreach($lines as $key => $value)
        {
            array_set($translations,$key,$value);
        }

        return $translations;

    }
}