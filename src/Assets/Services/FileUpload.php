<?php

namespace Chief\Assets\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUpload {

    private $filename;
    private $original;
    private $basepath;
    private $extension;
    private $mimetype;
    private $size;

    private $error;

    /**
     * @param UploadedFile $uploadedFile
     * @param $basepath
     * @param null $filename
     * @param null $basename
     * @param bool $force_unique force unique filename if custom filename is passed
     * @return bool
     */
    public function upload(UploadedFile $uploadedFile, $basepath, $filename = null, $basename = null, $force_unique = true)
    {
        if ( !$uploadedFile->isValid() )
        {
            $this->error = $uploadedFile->getErrorMessage();

            return false;
        }

        $this->original = $uploadedFile->getClientOriginalName();
        $this->basepath = $basepath;
        $this->extension = $uploadedFile->guessExtension();
        $this->mimetype = $uploadedFile->getMimeType();
        $this->size = $uploadedFile->getSize();

        $filename = $filename?: ($basename ? $this->getFilenameFromBasename($basename,$this->extension,$this->original) : $this->original);

        $this->filename = (!$force_unique) ? $filename : Util::generateUniqueFilename($filename, $basepath);

        $uploadedFile->move($this->basepath, $this->filename);

        return true;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function getBasepath()
    {
        return $this->basepath;
    }

    public function getExtension()
    {
        return $this->extension;
    }

    public function getMimetype()
    {
        return $this->mimetype;
    }

    public function getOriginalFilename()
    {
        return $this->original;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function getError()
    {
        return $this->error;
    }

    /**
     * Organic method to get the extension of the filename
     * Note that this is not a secure way of retrieving
     *
     * @param $basename
     * @param null $extension
     * @param $original
     * @return string
     */
    private function getFilenameFromBasename($basename, $extension = null, $original = null)
    {
        if(!$extension && !$original)
        {
            throw new \InvalidArgumentException('Either an extension or the original filename should be passed');
        }

        if(!$extension && $original)
        {
            $delimiter = strrpos($original,'.');
            $extension = substr($original,$delimiter+1);
        }

        return $basename.'.'.$extension;
    }
}
