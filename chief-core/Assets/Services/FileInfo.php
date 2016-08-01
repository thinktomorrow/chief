<?php

namespace Chief\Assets\Services;

use Symfony\Component\HttpFoundation\File\File as Symphonyfile;

class FileInfo{

    /**
     * @var string
     */
    protected $filepath;

    public function __construct($filepath)
    {
        $this->filepath = $filepath;
    }

    public static function make($filepath){ return new static($filepath); }

    /**
     * Get basic fileinfo
     *
     * @param   string  $key
     * @return 	object|string
     */
    public function get( $key = null )
    {
        if(!file_exists($this->filepath)) return false;

        $fileinfo = array(
            'extension'		=> strtolower(pathinfo($this->filepath, PATHINFO_EXTENSION)),
            'type'			=> filetype($this->filepath), // e.g. file or directory
            'size'			=> filesize($this->filepath),
            'last_modified' => filemtime($this->filepath) // timestamp
        );

        return is_null($key) ? (object)$fileinfo : $fileinfo[$key];
    }

    /**
     * Check if the file is an image
     *
     * @return   bool
     */
    public function isImage()
    {
        try
        {
            $mimes = ['image/jpg','image/jpeg','image/gif','image/png'];

            return in_array($this->getMimetype(),$mimes);
        }

        catch(\Exception $e)
        {
            $extensions = array('jpg','png','gif','jpeg');

            return (in_array($this->get('extension'),$extensions));
        }
    }

    /**
     * Get Mimetype, based on extension
     *
     * @return 	string
     */
    public function getMimetype()
    {
        $file = new Symphonyfile($this->filepath);

        return $file->getMimeType();
    }

}