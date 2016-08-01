<?php

namespace Chief\Trans\Handlers;


use Chief\Trans\Domain\Trans;

class SaveTranslationsToDisk
{
    /**
     * @var WriteTranslationLineToDisk
     */
    private $writer;

    public function __construct(WriteTranslationLineToDisk $writer)
    {
        $this->writer = $writer;
    }

    public function handle($locale = null)
    {
        Trans::getFlattenedTranslationLines($locale)->each(function($lines,$locale){
            $this->writer->write($locale,$lines);
        });
    }

    public function clear($locale = null)
    {
        app(ClearTranslationsOnDisk::class)->clear($locale);

        return $this;
    }

}